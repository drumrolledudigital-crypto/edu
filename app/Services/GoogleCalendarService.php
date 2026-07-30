<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Setting;
use Google\Service\Calendar;
use Google\Service\Calendar\ConferenceData;
use Google\Service\Calendar\ConferenceSolutionKey;
use Google\Service\Calendar\CreateConferenceRequest;
use Google\Service\Calendar\Event;
use Google\Service\Calendar\EventDateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleCalendarService
{
    public function __construct(private GoogleOAuthService $oauthService)
    {
    }

    public function calendar(): Calendar
    {
        return new Calendar($this->oauthService->authenticatedClient());
    }

    public function listCalendars(): array
    {
        $calendars = [];
        $calendarList = $this->calendar()->calendarList->listCalendarList();

        foreach ($calendarList->getItems() as $calendar) {
            $calendars[$calendar->getId()] = $calendar->getSummary();
        }

        return $calendars;
    }

    public function createOrUpdateEvent(Appointment $appointment, bool $withMeet = true): Appointment
    {
        $appointment->loadMissing(['student', 'subject', 'slot']);

        $calendar = $this->calendar();
        $calendarId = Setting::get('google_calendar_id', 'primary') ?: 'primary';
        $event = $this->buildEvent($appointment, $withMeet);

        try {
            if ($appointment->calendar_event_id || $appointment->google_calendar_event_id) {
                $eventId = $appointment->google_calendar_event_id ?: $appointment->calendar_event_id;
                $event = $calendar->events->patch($calendarId, $eventId, $event, [
                    'conferenceDataVersion' => 1,
                    'sendUpdates' => 'all',
                ]);
                $status = 'updated';
                $calStatus = 'Updated';
            } else {
                $event = $calendar->events->insert($calendarId, $event, [
                    'conferenceDataVersion' => 1,
                    'sendUpdates' => 'all',
                ]);
                $status = 'synced';
                $calStatus = 'Created';
            }
        } catch (\Google\Service\Exception $e) {
            $error = json_decode($e->getMessage(), true);
            $code = $error['error']['code'] ?? $e->getCode();
            $message = $error['error']['message'] ?? $e->getMessage();

            if ($code === 403 && str_contains($message, 'requiredAccessLevel') && $calendarId !== 'primary') {
                Log::warning("Google Calendar 403 on calendar [{$calendarId}], falling back to primary. Error: {$message}");

                $event = $this->buildEvent($appointment, $withMeet);
                $event = $calendar->events->insert('primary', $event, [
                    'conferenceDataVersion' => 1,
                    'sendUpdates' => 'all',
                ]);
                $status = 'synced';
                $calStatus = 'Created';
                Setting::set('google_calendar_id', 'primary', 'google');
            } else {
                throw $e;
            }
        }

        $meetLink = $this->meetLinkFromEvent($event) ?: $appointment->meet_link;

        $appointment->forceFill([
            'meet_link' => $meetLink,
            'meet_event_id' => $event->getId(),
            'meet_status' => $meetLink ? 'generated' : $appointment->meet_status,
            'meet_metadata' => $event->getConferenceData() ? json_decode(json_encode($event->getConferenceData()), true) : $appointment->meet_metadata,
            'meet_generated_at' => $meetLink && !$appointment->meet_generated_at ? now() : $appointment->meet_generated_at,
            'google_meet_link' => $meetLink,
            'google_meet_id' => $event->getId(),
            'meeting_status' => $meetLink ? 'generated' : 'pending',
            'meeting_generated_at' => $meetLink && !$appointment->meeting_generated_at ? now() : $appointment->meeting_generated_at,
            'calendar_event_id' => $event->getId(),
            'calendar_sync_status' => $status,
            'calendar_last_synced_at' => now(),
            'google_calendar_event_id' => $event->getId(),
            'calendar_status' => $calStatus,
            'calendar_created_at' => $appointment->calendar_created_at ?: now(),
            'calendar_updated_at' => now(),
        ])->save();

        return $appointment->refresh();
    }

    public function deleteEvent(Appointment $appointment): Appointment
    {
        $eventId = $appointment->google_calendar_event_id ?: $appointment->calendar_event_id;
        if (!$eventId) {
            $appointment->forceFill([
                'calendar_sync_status' => 'removed',
                'calendar_last_synced_at' => now(),
                'calendar_status' => 'Cancelled',
                'calendar_updated_at' => now(),
            ])->save();

            return $appointment->refresh();
        }

        $calendarId = Setting::get('google_calendar_id', 'primary') ?: 'primary';
        $this->calendar()->events->delete($calendarId, $eventId, [
            'sendUpdates' => 'all',
        ]);

        $appointment->forceFill([
            'calendar_event_id' => null,
            'google_calendar_event_id' => null,
            'calendar_sync_status' => 'removed',
            'calendar_last_synced_at' => now(),
            'calendar_status' => 'Cancelled',
            'calendar_updated_at' => now(),
        ])->save();

        return $appointment->refresh();
    }

    public function getEventLink(Appointment $appointment): ?string
    {
        if (!$appointment->calendar_event_id) {
            return null;
        }

        $calendarId = Setting::get('google_calendar_id', 'primary') ?: 'primary';
        $event = $this->calendar()->events->get($calendarId, $appointment->calendar_event_id);

        return $event->getHtmlLink();
    }

    private function buildEvent(Appointment $appointment, bool $withMeet): Event
    {
        $start = $this->appointmentDateTime($appointment, $appointment->slot->start_time);
        $end = $this->appointmentDateTime($appointment, $appointment->slot->end_time);
        $timezone = config('app.timezone', 'UTC');

        $event = new Event([
            'summary' => trim(($appointment->subject?->name ?? 'Doubt Solving') . ' Session - ' . ($appointment->student?->name ?? 'Student')),
            'description' => $this->description($appointment),
            'start' => new EventDateTime([
                'dateTime' => $start->toRfc3339String(),
                'timeZone' => $timezone,
            ]),
            'end' => new EventDateTime([
                'dateTime' => $end->toRfc3339String(),
                'timeZone' => $timezone,
            ]),
            'attendees' => array_filter([
                $appointment->student?->email ? ['email' => $appointment->student->email, 'displayName' => $appointment->student->name] : null,
            ]),
        ]);

        if ($withMeet) {
            $conferenceKey = new ConferenceSolutionKey();
            $conferenceKey->setType('hangoutsMeet');

            $conferenceRequest = new CreateConferenceRequest();
            $conferenceRequest->setRequestId('appointment-' . $appointment->id . '-' . Str::uuid()->toString());
            $conferenceRequest->setConferenceSolutionKey($conferenceKey);

            $conferenceData = new ConferenceData();
            $conferenceData->setCreateRequest($conferenceRequest);
            $event->setConferenceData($conferenceData);
        }

        return $event;
    }

    private function description(Appointment $appointment): string
    {
        return implode("\n", array_filter([
            'Student: ' . ($appointment->student?->name ?? 'N/A'),
            'Student Email: ' . ($appointment->student?->email ?? 'N/A'),
            'Subject: ' . ($appointment->subject?->name ?? 'N/A'),
            'Session Date: ' . ($appointment->slot?->date ?? 'N/A'),
            'Session Time: ' . ($appointment->slot?->start_time ?? 'N/A') . ' - ' . ($appointment->slot?->end_time ?? 'N/A'),
            'Duration: ' . $this->durationMinutes($appointment) . ' minutes',
            $appointment->meet_link ? 'Meet Link: ' . $appointment->meet_link : null,
            $appointment->admin_notes ? 'Admin Notes: ' . $appointment->admin_notes : null,
        ]));
    }

    private function appointmentDateTime(Appointment $appointment, string $time)
    {
        $date = $appointment->slot->date instanceof \Carbon\Carbon
            ? $appointment->slot->date->format('Y-m-d')
            : $appointment->slot->date;

        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $time, config('app.timezone', 'UTC'));
    }

    private function durationMinutes(Appointment $appointment): int
    {
        return $this->appointmentDateTime($appointment, $appointment->slot->start_time)
            ->diffInMinutes($this->appointmentDateTime($appointment, $appointment->slot->end_time));
    }

    public function diagnoseCalendarAccess(?string $calendarId = null): array
    {
        $calendarId = $calendarId ?: Setting::get('google_calendar_id', 'primary') ?: 'primary';
        $account = $this->oauthService->connectedAccount();

        $result = [
            'connected_email' => $account?->google_email ?? 'Not connected',
            'calendar_id' => $calendarId,
            'calendar_name' => null,
            'calendar_owner' => null,
            'access_level' => null,
            'can_write' => false,
            'meet_supported' => false,
            'primary_fallback_available' => true,
            'error' => null,
            'recommendation' => null,
        ];

        try {
            $calendar = $this->calendar();
            $calList = $calendar->calendarList->get($calendarId);
            $result['calendar_name'] = $calList->getSummary();
            $result['access_level'] = $calList->getAccessRole() ?? 'unknown';
            $result['can_write'] = in_array($result['access_level'], ['owner', 'writer']);
        } catch (\Google\Service\Exception $e) {
            $error = json_decode($e->getMessage(), true);
            $result['error'] = $error['error']['message'] ?? $e->getMessage();

            if ($calendarId !== 'primary') {
                try {
                    $calList = $calendar->calendarList->get('primary');
                    $result['primary_fallback_available'] = true;
                } catch (\Throwable $e2) {
                    $result['primary_fallback_available'] = false;
                }
            }
        } catch (\Throwable $e) {
            $result['error'] = $e->getMessage();
        }

        if (!$result['can_write'] && $calendarId !== 'primary') {
            $result['recommendation'] = 'Switch to primary calendar. The connected account has ' . ($result['access_level'] ?? 'no') . ' access to this calendar.';
        } elseif (!$result['can_write'] && $calendarId === 'primary') {
            $result['recommendation'] = 'Check Google account connection. Primary calendar should have owner access.';
        } else {
            $result['recommendation'] = 'Calendar is writable. No action needed.';
        }

        return $result;
    }

    public function testMeetConference(?string $calendarId = null): array
    {
        $calendarId = $calendarId ?: Setting::get('google_calendar_id', 'primary') ?: 'primary';
        $timezone = config('app.timezone', 'UTC');
        $now = now()->timezone($timezone);

        $result = [
            'success' => false,
            'calendar_id' => $calendarId,
            'event_id' => null,
            'meet_link' => null,
            'error' => null,
        ];

        try {
            $calendar = $this->calendar();

            $conferenceKey = new ConferenceSolutionKey();
            $conferenceKey->setType('hangoutsMeet');

            $conferenceRequest = new CreateConferenceRequest();
            $conferenceRequest->setRequestId('test-' . Str::uuid()->toString());
            $conferenceRequest->setConferenceSolutionKey($conferenceKey);

            $conferenceData = new ConferenceData();
            $conferenceData->setCreateRequest($conferenceRequest);

            $event = new Event([
                'summary' => '[Drumroll Edu Test] Calendar Access Verification',
                'description' => 'This is an automated test event. It will be deleted immediately.',
                'start' => new EventDateTime([
                    'dateTime' => $now->addHour()->toRfc3339String(),
                    'timeZone' => $timezone,
                ]),
                'end' => new EventDateTime([
                    'dateTime' => $now->addHour()->addMinutes(15)->toRfc3339String(),
                    'timeZone' => $timezone,
                ]),
            ]);
            $event->setConferenceData($conferenceData);

            $createdEvent = $calendar->events->insert($calendarId, $event, [
                'conferenceDataVersion' => 1,
            ]);

            $result['event_id'] = $createdEvent->getId();
            $result['meet_link'] = $this->meetLinkFromEvent($createdEvent);
            $result['success'] = true;

            try {
                $calendar->events->delete($calendarId, $createdEvent->getId());
            } catch (\Throwable $e) {
                Log::warning("Failed to delete test event: " . $e->getMessage());
            }
        } catch (\Google\Service\Exception $e) {
            $error = json_decode($e->getMessage(), true);
            $result['error'] = $error['error']['message'] ?? $e->getMessage();

            if ($calendarId !== 'primary') {
                try {
                    $result['calendar_id'] = 'primary';
                    $result['error'] = $result['error'] . ' (fallback to primary not attempted automatically)';
                } catch (\Throwable $e2) {
                    // ignore
                }
            }
        } catch (\Throwable $e) {
            $result['error'] = $e->getMessage();
        }

        return $result;
    }

    public function switchToPrimaryCalendar(): bool
    {
        try {
            $calendar = $this->calendar();
            $calendar->calendarList->get('primary');
            Setting::set('google_calendar_id', 'primary', 'google');
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function validateCalendarId(string $calendarId): array
    {
        try {
            $calendar = $this->calendar();
            $calList = $calendar->calendarList->get($calendarId);

            return [
                'valid' => true,
                'calendar_id' => $calendarId,
                'name' => $calList->getSummary(),
                'access_level' => $calList->getAccessRole(),
                'can_write' => in_array($calList->getAccessRole(), ['owner', 'writer']),
            ];
        } catch (\Google\Service\Exception $e) {
            $error = json_decode($e->getMessage(), true);
            return [
                'valid' => false,
                'calendar_id' => $calendarId,
                'error' => $error['error']['message'] ?? $e->getMessage(),
                'can_write' => false,
            ];
        }
    }

    private function meetLinkFromEvent(Event $event): ?string
    {
        if ($event->getHangoutLink()) {
            return $event->getHangoutLink();
        }

        $conferenceData = $event->getConferenceData();
        if (!$conferenceData || !$conferenceData->getEntryPoints()) {
            return null;
        }

        foreach ($conferenceData->getEntryPoints() as $entryPoint) {
            if ($entryPoint->getEntryPointType() === 'video') {
                return $entryPoint->getUri();
            }
        }

        return null;
    }
}
