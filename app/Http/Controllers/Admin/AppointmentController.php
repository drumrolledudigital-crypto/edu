<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Slot;
use App\Services\IntegrationAutomationService;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('admin.appointments.index');
    }

    public function list()
    {
        $appointments = Appointment::with(['student', 'subject', 'doubt', 'slot'])->latest()->get();
        return response()->json([
            'status' => 'success',
            'data' => $appointments
        ]);
    }

    public function show($id)
    {
        $appointment = Appointment::with(['student', 'subject', 'doubt', 'slot', 'emailLogs' => fn ($query) => $query->latest()])->findOrFail($id);
        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $appointment
            ]);
        }
        return view('admin.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, $id, IntegrationAutomationService $automation, \App\Services\AuditLoggerService $logger)
    {
        $appointment = Appointment::findOrFail($id);
        $previousStatus = $appointment->status;
        $oldData = $appointment->toArray();

        $request->validate([
            'status' => 'required|in:pending,scheduled,confirmed,completed,cancelled,rescheduled',
        ]);

        $appointment->status = $request->status;
        $appointment->save();

        // Safe automation call
        try {
            $automation->automateForStatus($appointment->refresh(), $previousStatus);
        } catch (\Throwable $e) {
            // Log error
            \Illuminate\Support\Facades\Log::error('Automation failed on status update: ' . $e->getMessage());
        }

        // If cancelled, free the slot
        if ($request->status === 'cancelled') {
            Slot::where('id', $appointment->slot_id)->update(['status' => 'available']);
        }
        
        $logger->log('Booking', 'StatusUpdate', "Appointment #{$id} status changed from {$previousStatus} to {$request->status}.", $oldData, $appointment->toArray());

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment status updated successfully'
        ]);
    }

    public function regenerateMeet($id, IntegrationAutomationService $automation)
    {
        $appointment = Appointment::findOrFail($id);

        try {
            $automation->generateMeetLink($appointment, true);
            return response()->json([
                'status' => 'success',
                'message' => 'Google Meet link regenerated successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to regenerate meet link. Please check Google integration settings.'
            ], 500);
        }
    }

    public function syncCalendar($id, IntegrationAutomationService $automation)
    {
        $appointment = Appointment::findOrFail($id);

        try {
            $automation->syncCalendarEvent($appointment, true);
            return response()->json([
                'status' => 'success',
                'message' => 'Google Calendar event synchronized successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to sync calendar. Please check Google integration settings.'
            ], 500);
        }
    }

    public function destroy($id, IntegrationAutomationService $automation, AuditLoggerService $logger)
    {
        $appointment = Appointment::findOrFail($id);
        $oldData = $appointment->toArray();

        DB::transaction(function () use ($appointment, $automation, $id, $logger, $oldData) {
            if ($appointment->google_calendar_event_id || $appointment->calendar_event_id) {
                try {
                    $automation->removeCalendarEvent($appointment);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to clean up calendar event on appointment delete: ' . $e->getMessage());
                }
            }

            Slot::where('id', $appointment->slot_id)->update(['status' => 'available']);
            $appointment->delete();
        });

        $logger->log('Appointment', 'Delete', "Appointment #{$id} was deleted.", $oldData);

        return response()->json([
            'status' => 'success',
            'message' => 'Appointment record removed'
        ]);
    }

    public function create()
    {
        return view('admin.appointments.create');
    }

    public function store(Request $request, IntegrationAutomationService $automation, AuditLoggerService $logger, \App\Services\InvoiceService $invoiceService)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject_id' => 'required|exists:subjects,id',
            'doubt_type' => 'required|in:existing,new',
            'doubt_id' => 'required_if:doubt_type,existing|nullable|exists:doubts,id',
            'doubt_topic' => 'required_if:doubt_type,new|nullable|string|max:255',
            'doubt_title' => 'required_if:doubt_type,new|nullable|string|max:255',
            'doubt_description' => 'required_if:doubt_type,new|nullable|string',
            'slot_id' => 'required|exists:slots,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'duration' => 'required|integer|min:10',
            'payment_status' => 'required|in:paid,pending,waived,manual',
            'amount' => 'required_if:payment_status,paid|required_if:payment_status,manual|nullable|numeric|min:0',
            'booking_status' => 'required|in:pending,scheduled,confirmed',
            'admin_notes' => 'nullable|string',
        ]);

        $student = \App\Models\User::where('role', 'student')->where('is_active', true)->findOrFail($request->student_id);
        $subject = \App\Models\Subject::where('status', 'active')->findOrFail($request->subject_id);

        $doubt = null;
        if ($request->doubt_type === 'existing') {
            $doubt = \App\Models\Doubt::where('user_id', $student->id)->findOrFail($request->doubt_id);
            if ($doubt->appointment()->exists()) {
                return back()->withErrors(['doubt_id' => 'A session is already booked for this doubt.']);
            }
        } else {
            $doubt = \App\Models\Doubt::create([
                'user_id' => $student->id,
                'subject_id' => $subject->id,
                'topic_name' => $request->doubt_topic,
                'title' => $request->doubt_title,
                'description' => $request->doubt_description,
                'status' => 'pending',
            ]);
        }

        $appointment = DB::transaction(function () use ($request, $student, $subject, $doubt) {
            $slot = Slot::where('id', $request->slot_id)
                ->where('status', 'available')
                ->lockForUpdate()
                ->first();

            if (!$slot) {
                return null;
            }

            $slot->update(['status' => 'booked']);

            return Appointment::create([
                'user_id' => $student->id,
                'subject_id' => $subject->id,
                'doubt_id' => $doubt->id,
                'slot_id' => $slot->id,
                'appointment_date' => $request->appointment_date,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'duration' => $request->duration,
                'status' => $request->booking_status,
                'admin_notes' => $request->admin_notes,
            ]);
        });

        if (!$appointment) {
            return back()->withErrors(['slot_id' => 'This slot is no longer available.'])->withInput();
        }

        $currency = \App\Models\Setting::get('currency', 'USD');
        $payment = null;

        if (in_array($request->payment_status, ['paid', 'manual'])) {
            $payment = \App\Models\Payment::create([
                'student_id' => $student->id,
                'appointment_id' => $appointment->id,
                'amount' => $request->amount,
                'currency' => $currency,
                'payment_status' => 'successful',
                'payment_date' => now(),
            ]);
            try {
                $invoiceService->generateForPayment($payment);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Invoice generation failed: ' . $e->getMessage());
            }
        } elseif ($request->payment_status === 'pending') {
            $payment = \App\Models\Payment::create([
                'student_id' => $student->id,
                'appointment_id' => $appointment->id,
                'amount' => $request->amount ?? 0,
                'currency' => $currency,
                'payment_status' => 'pending',
                'payment_date' => now(),
            ]);
        } elseif ($request->payment_status === 'waived') {
            $payment = \App\Models\Payment::create([
                'student_id' => $student->id,
                'appointment_id' => $appointment->id,
                'amount' => 0,
                'currency' => $currency,
                'payment_status' => 'successful',
                'payment_date' => now(),
            ]);
        }

        try {
            $automation->generateMeetLink($appointment);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Meet link generation failed: ' . $e->getMessage());
        }

        try {
            $automation->syncCalendarEvent($appointment);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Calendar sync failed: ' . $e->getMessage());
        }

        try {
            $automation->sendAppointmentEmail($appointment->load(['student', 'subject', 'doubt']), 'appointment_created');
            $automation->createInternalAdminNotification(
                'Booking',
                'New Session Booked (Admin)',
                'Admin created a booking for ' . $student->name . ' in ' . $subject->name . '.',
                $appointment,
                $student,
                route('admin.appointments.show', $appointment->id),
                'calendar'
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Email/notification failed: ' . $e->getMessage());
        }

        $logger->log('Booking', 'AdminCreate', "Admin created booking for '{$student->name}' in '{$subject->name}'.", null, $appointment->toArray());

        return redirect()->route('admin.appointments.show', $appointment->id)->with('success', 'Booking created successfully!');
    }

    public function apiSearchStudents(Request $request)
    {
        $query = $request->input('q', '');

        $students = \App\Models\User::where('role', 'student')
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(15)
            ->get(['id', 'name', 'email', 'student_class']);

        return response()->json(['status' => 'success', 'data' => $students]);
    }

    public function apiActiveSubjects()
    {
        $subjects = \App\Models\Subject::where('status', 'active')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'session_duration']);

        return response()->json(['status' => 'success', 'data' => $subjects]);
    }

    public function apiStudentDoubts(Request $request, $userId)
    {
        $query = \App\Models\Doubt::where('user_id', $userId)
            ->where('status', '!=', 'cancelled')
            ->whereDoesntHave('appointment', function ($q) {
                $q->where('status', '!=', 'cancelled');
            })
            ->with('subject');

        if ($request->has('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $doubts = $query->latest()->get();

        return response()->json(['status' => 'success', 'data' => $doubts]);
    }

    public function apiAvailableSlots(Request $request)
    {
        $date = $request->input('date');

        $now = now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $slots = \App\Models\Slot::where('status', 'available')
            ->where(function ($query) use ($today, $currentTime) {
                $query->where('date', '>', $today)
                      ->orWhere(function ($q) use ($today, $currentTime) {
                          $q->where('date', '=', $today)
                            ->where('start_time', '>', $currentTime);
                      });
            })
            ->when($date, fn ($q) => $q->where('date', $date))
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return response()->json(['status' => 'success', 'data' => $slots]);
    }

    public function sendEmail($id, IntegrationAutomationService $automation)
    {
        $appointment = Appointment::with(['student', 'subject', 'doubt'])->findOrFail($id);

        try {
            $automation->sendAppointmentEmail($appointment, 'appointment_created');
            return response()->json([
                'status' => 'success',
                'message' => 'Email sent successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateInvoice($id, \App\Services\InvoiceService $invoiceService)
    {
        $appointment = Appointment::findOrFail($id);
        $payment = $appointment->payment;

        if (!$payment) {
            $payment = \App\Models\Payment::create([
                'student_id' => $appointment->user_id,
                'appointment_id' => $appointment->id,
                'amount' => \App\Models\Setting::get('session_price', '32.00'),
                'currency' => \App\Models\Setting::get('currency', 'USD'),
                'payment_status' => 'successful',
                'payment_date' => now(),
            ]);
        }

        try {
            $invoiceService->generateForPayment($payment);
            return response()->json([
                'status' => 'success',
                'message' => 'Invoice generated successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to generate invoice: ' . $e->getMessage()
            ], 500);
        }
    }

    public function printBooking($id)
    {
        $appointment = Appointment::with(['student', 'subject', 'doubt', 'slot', 'payment'])->findOrFail($id);
        return view('admin.appointments.print', compact('appointment'));
    }
}
