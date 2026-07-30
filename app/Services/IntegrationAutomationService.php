<?php

namespace App\Services;

use App\Jobs\SendLoggedEmail;
use App\Models\Appointment;
use App\Models\EmailLog;
use App\Models\Setting;

class IntegrationAutomationService
{
    public function __construct(
        private GoogleMeetService $meetService,
        private GoogleCalendarService $calendarService,
        private SmtpMailService $mailService,
    ) {
    }

    public function automateForStatus(Appointment $appointment, ?string $previousStatus = null, bool $slotChanged = false): void
    {
        if ($appointment->status === 'confirmed') {
            if (!$appointment->meet_link || !$appointment->google_calendar_event_id) {
                $this->generateMeetLink($appointment);
            } elseif (Setting::get('google_calendar_auto_create', '1') === '1') {
                $this->syncCalendarEvent($appointment);
            }

            $this->sendAppointmentEmail($appointment, $previousStatus === 'confirmed' ? 'meeting_updated' : 'appointment_confirmed');

            // Send payment email if confirmed for the first time
            if ($previousStatus !== 'confirmed' && $appointment->payment) {
                $this->sendPaymentEmail($appointment->payment);
            }
        }

        if ($appointment->status === 'rescheduled' || $slotChanged) {
            $appointment->forceFill(['rescheduled_at' => now()])->save();

            if (Setting::get('google_calendar_auto_update', '1') === '1') {
                $this->syncCalendarEvent($appointment, true);
            }

            $this->sendAppointmentEmail($appointment, 'appointment_rescheduled');
        }

        if ($appointment->status === 'cancelled') {
            if (Setting::get('google_calendar_auto_delete', '1') === '1') {
                $this->removeCalendarEvent($appointment);
            } else {
                $this->meetService->cancelMeeting($appointment);
            }

            $this->sendAppointmentEmail($appointment, 'appointment_cancelled');
        }
    }

    public function generateMeetLink(Appointment $appointment, bool $regenerate = false): Appointment
    {
        if (!$regenerate && $appointment->meet_link) {
            return $appointment;
        }

        try {
            return $this->meetService->createMeeting($appointment);
        } catch (\Throwable $exception) {
            $appointment->forceFill([
                'meet_status' => 'failed',
                'meeting_status' => 'failed',
                'meet_metadata' => ['error' => $exception->getMessage()],
            ])->save();

            return $appointment->refresh();
        }
    }

    public function syncCalendarEvent(Appointment $appointment, bool $update = false): Appointment
    {
        try {
            return $this->calendarService->createOrUpdateEvent($appointment, true);
        } catch (\Throwable $exception) {
            $appointment->forceFill([
                'calendar_sync_status' => 'failed',
                'calendar_last_synced_at' => now(),
                'meet_metadata' => array_merge($appointment->meet_metadata ?? [], ['calendar_error' => $exception->getMessage()]),
            ])->save();

            return $appointment->refresh();
        }
    }

    public function removeCalendarEvent(Appointment $appointment): Appointment
    {
        try {
            $this->meetService->cancelMeeting($appointment);
            return $this->calendarService->deleteEvent($appointment);
        } catch (\Throwable $exception) {
            $appointment->forceFill([
                'calendar_sync_status' => 'failed',
                'calendar_last_synced_at' => now(),
                'meet_metadata' => array_merge($appointment->meet_metadata ?? [], ['calendar_delete_error' => $exception->getMessage()]),
            ])->save();

            return $appointment->refresh();
        }
    }

    public function createInternalAdminNotification(string $type, string $title, string $message, $related = null, ?\App\Models\User $user = null, ?string $url = null, ?string $icon = null)
    {
        try {
            \App\Models\AdminNotification::create([
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'related_id' => $related ? $related->id : null,
                'related_type' => $related ? get_class($related) : null,
                'user_id' => $user ? $user->id : null,
                'url' => $url,
                'icon' => $icon ?? 'bell',
                'status' => 'unread',
            ]);
        } catch (\Throwable $th) {
            \Illuminate\Support\Facades\Log::error('Failed to create admin notification: ' . $th->getMessage());
        }
    }

    public function sendAdminNotification(string $type, array $data = []): ?EmailLog
    {
        $enabledKey = 'notify_' . $type;
        if (Setting::get($enabledKey, '1') !== '1') {
            return null;
        }

        $adminEmail = Setting::get('contact_email');
        if (!$adminEmail) {
            return null;
        }

        $template = \App\Models\NotificationTemplate::where('key', 'admin_' . $type)->first();
        $subject = $template?->subject ?? 'Admin Notification';
        $content = $data['message'] ?? $template?->body ?? 'An automated event occurred on the platform.';

        $log = EmailLog::create([
            'recipient' => $adminEmail,
            'subject' => $subject,
            'type' => 'admin_' . $type,
            'status' => 'pending',
        ]);

        try {
            SendLoggedEmail::dispatch($log->id, 'emails.layout', [
                'title' => $subject,
                'content' => $content
            ]);
        } catch (\Throwable $exception) {
            $log->update(['status' => 'failed', 'error_message' => $exception->getMessage()]);
        }

        return $log->refresh();
    }

    public function sendWelcomeEmail(\App\Models\User $user): EmailLog
    {
        $log = EmailLog::create([
            'recipient' => $user->email,
            'subject' => 'Welcome to ' . Setting::get('platform_name', 'Drumroll Edu'),
            'type' => 'welcome_student',
            'status' => 'pending',
        ]);

        try {
            SendLoggedEmail::dispatch($log->id, 'emails.welcome', ['user' => $user]);
            
            // Notify Admin
            $this->sendAdminNotification('new_registration', [
                'message' => "A new student, {$user->name} ({$user->email}), has registered on the platform."
            ]);
        } catch (\Throwable $exception) {
            $log->update(['status' => 'failed', 'error_message' => $exception->getMessage()]);
        }

        return $log->refresh();
    }

    public function sendPaymentEmail(\App\Models\Payment $payment): EmailLog
    {
        $appointment = $payment->appointment;
        $log = EmailLog::create([
            'recipient' => $appointment->student->email,
            'subject' => 'Payment Successful - Session #' . $appointment->id,
            'type' => 'payment_success',
            'status' => 'pending',
            'appointment_id' => $appointment->id,
        ]);

        try {
            SendLoggedEmail::dispatch($log->id, 'emails.payment', [
                'payment' => $payment,
                'appointment' => $appointment,
            ]);

            // Notify Admin
            $this->sendAdminNotification('payment_received', [
                'message' => "Payment of {$payment->currency} {$payment->amount} received for Appointment #{$appointment->id} from {$appointment->student->name}."
            ]);
        } catch (\Throwable $exception) {
            $log->update(['status' => 'failed', 'error_message' => $exception->getMessage()]);
        }

        return $log->refresh();
    }

    public function sendAppointmentEmail(Appointment $appointment, string $type): EmailLog
    {
        $appointment->loadMissing(['student', 'subject', 'slot']);
        $student = $appointment->student;

        $types = [
            'appointment_created' => [
                'subject' => 'New Session Booking Created',
                'title' => 'Session Booked Successfully!',
                'greeting' => "Hello {$student?->name},",
                'intro' => 'Your session booking has been created. It is currently pending payment confirmation. Once payment is received, your session will be fully confirmed.',
                'admin_type' => 'new_booking',
                'admin_message' => "Student {$student?->name} has booked a new session for {$appointment->subject?->name} on {$appointment->appointment_date->format('M d')}."
            ],
            'appointment_confirmed' => [
                'subject' => 'Your Session is Confirmed',
                'title' => 'Session Confirmed!',
                'greeting' => "Hello {$student?->name},",
                'intro' => 'Good news! Your session has been confirmed. You can find the joining details below.',
            ],
            'appointment_rescheduled' => [
                'subject' => 'Your Session has been Rescheduled',
                'title' => 'Schedule Update',
                'greeting' => "Hello {$student?->name},",
                'intro' => 'Your upcoming session has been rescheduled. Please review the updated time and joining details below.',
                'admin_type' => 'appointment_rescheduled',
                'admin_message' => "Appointment #{$appointment->id} for {$student?->name} has been rescheduled to {$appointment->appointment_date->format('M d')} at {$appointment->start_time}."
            ],
            'appointment_cancelled' => [
                'subject' => 'Your Session has been Cancelled',
                'title' => 'Session Cancellation',
                'greeting' => "Hello {$student?->name},",
                'intro' => 'Your session booking has been cancelled. If this was unexpected, please contact support.',
                'admin_type' => 'appointment_cancelled',
                'admin_message' => "Appointment #{$appointment->id} for {$student?->name} has been cancelled."
            ],
            'meeting_reminder' => [
                'subject' => 'Reminder: Your Session starts soon',
                'title' => 'Session Reminder',
                'greeting' => "Hello {$student?->name},",
                'intro' => "This is a friendly reminder that your session starts in about an hour. We're looking forward to seeing you!",
            ],
            'meeting_updated' => [
                'subject' => 'Update: Your Session Meeting Details',
                'title' => 'Meeting Details Updated',
                'greeting' => "Hello {$student?->name},",
                'intro' => 'The meeting details for your upcoming session have been updated. Please use the new link provided below.',
            ],
        ];

        // Try to load overrides from NotificationTemplate
        $template = \App\Models\NotificationTemplate::where('key', 'email_' . $type)->first();
        if ($template) {
            $config = [
                'subject' => $template->subject,
                'title' => $template->subject,
                'greeting' => "Hello {$student?->name},",
                'intro' => $template->body,
            ] + ($types[$type] ?? []);
        } else {
            $config = $types[$type] ?? [
                'subject' => 'Session Notification',
                'title' => 'Session Update',
                'greeting' => "Hello {$student?->name},",
                'intro' => 'There has been an update to your session booking.',
            ];
        }

        $log = EmailLog::create([
            'recipient' => $student?->email ?? Setting::get('contact_email'),
            'subject' => $config['subject'] . ' - ' . ($appointment->subject?->name ?? Setting::get('platform_name', 'Drumroll Edu')),
            'type' => $type,
            'status' => 'pending',
            'appointment_id' => $appointment->id,
        ]);

        if (!$this->mailService->isConfigured()) {
            $error = 'SMTP Not Configured. Missing: ' . implode(', ', $this->mailService->missingFields());
            $log->update(['status' => 'failed', 'error_message' => $error]);
            $appointment->forceFill(['email_notification_status' => 'failed'])->save();
            return $log->refresh();
        }

        try {
            SendLoggedEmail::dispatch($log->id, 'emails.appointment', array_merge($config, [
                'appointment' => $appointment,
                'button_url' => route('student.dashboard'),
                'button_text' => 'Go to Dashboard',
            ]));
            $appointment->forceFill(['email_notification_status' => 'pending'])->save();

            // Notify Admin if applicable
            if (isset($config['admin_type'])) {
                $this->sendAdminNotification($config['admin_type'], ['message' => $config['admin_message']]);
            }
        } catch (\Throwable $exception) {
            $log->update(['status' => 'failed', 'error_message' => $exception->getMessage()]);
            $appointment->forceFill(['email_notification_status' => 'failed'])->save();
        }

        return $log->refresh();
    }

    public function sendEmailNow(EmailLog $log, string $view, array $data = []): EmailLog
    {
        $result = $this->mailService->sendLoggedEmail($log, $view, $data);

        if ($log->appointment) {
            $log->appointment->forceFill([
                'email_notification_status' => $result->status,
                'email_notification_sent_at' => $result->sent_at,
            ])->save();
        }

        return $result;
    }

    public function sendTestEmail(string $recipient): EmailLog
    {
        $log = EmailLog::create([
            'recipient' => $recipient,
            'subject' => 'SMTP Test Email',
            'type' => 'smtp_test',
            'status' => 'pending',
        ]);

        try {
            return $this->mailService->sendLoggedEmail($log, 'emails.layout', [
                'title' => 'SMTP Test',
                'content' => 'Your ' . Setting::get('platform_name', 'Drumroll Edu') . ' SMTP configuration is working successfully!'
            ]);
        } catch (\Throwable) {
            return $log->refresh();
        }
    }
}
