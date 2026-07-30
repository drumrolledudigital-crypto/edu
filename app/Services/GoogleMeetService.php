<?php

namespace App\Services;

use App\Models\Appointment;

class GoogleMeetService
{
    public function __construct(private GoogleCalendarService $calendarService)
    {
    }

    public function createMeeting(Appointment $appointment): Appointment
    {
        return $this->calendarService->createOrUpdateEvent($appointment, true);
    }

    public function updateMeeting(Appointment $appointment): Appointment
    {
        return $this->calendarService->createOrUpdateEvent($appointment, true);
    }

    public function cancelMeeting(Appointment $appointment): Appointment
    {
        // Delete the Google Calendar event (which also removes the Meet link)
        $appointment = $this->calendarService->deleteEvent($appointment);

        // Update local status fields
        $appointment->forceFill([
            'meet_status' => 'cancelled',
            'meeting_status' => 'cancelled',
        ])->save();

        return $appointment->refresh();
    }
}
