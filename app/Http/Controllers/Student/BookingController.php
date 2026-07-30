<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doubt;
use App\Models\Subject;
use App\Models\Slot;
use App\Services\IntegrationAutomationService;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display the booking page with subject selection and weekly calendar.
     */
    public function create()
    {
        $subjects = Subject::where('status', 'active')->orderBy('sort_order')->orderBy('name')->get();

        $today = now()->startOfDay();
        $sixWeeksLater = $today->copy()->addWeeks(6);
        $currentTime = now()->format('H:i:s');

        $slots = Slot::where('status', 'available')
            ->where('date', '>=', $today->toDateString())
            ->where('date', '<=', $sixWeeksLater->toDateString())
            ->where(function ($query) use ($today, $currentTime) {
                $query->where('date', '>', $today->toDateString())
                      ->orWhere(function ($q) use ($today, $currentTime) {
                          $q->where('date', '=', $today->toDateString())
                            ->where('start_time', '>', $currentTime);
                      });
            })
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $groupedSlots = $slots->groupBy('date');

        $weeks = [];
        $currentWeekStart = $today->copy()->startOfWeek();
        for ($w = 0; $w < 6; $w++) {
            $weekStart = $currentWeekStart->copy()->addWeeks($w);
            $days = [];
            for ($d = 0; $d < 7; $d++) {
                $date = $weekStart->copy()->addDays($d);
                $dateString = $date->toDateString();
                $days[] = [
                    'date' => $dateString,
                    'day' => $date->format('D'),
                    'dayNum' => $date->format('d'),
                    'month' => $date->format('M'),
                    'isPast' => $date->isBefore($today),
                    'isToday' => $date->isSameDay($today),
                    'hasSlots' => isset($groupedSlots[$dateString]),
                    'slots' => isset($groupedSlots[$dateString]) ? $groupedSlots[$dateString]->toArray() : [],
                ];
            }
            $weeks[] = [
                'start' => $weekStart->toDateString(),
                'end' => $weekStart->copy()->endOfWeek()->toDateString(),
                'days' => $days,
            ];
        }

        return view('student.booking.create', compact('subjects', 'weeks'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request, IntegrationAutomationService $automation, AuditLoggerService $logger)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'slot_id' => 'required|exists:slots,id',
            'doubt_id' => 'nullable|exists:doubts,id',
        ]);

        $subject = Subject::findOrFail($request->subject_id);

        if ($request->filled('doubt_id')) {
            $doubt = Doubt::findOrFail($request->doubt_id);
            if ($doubt->user_id !== Auth::id()) abort(403);
        }

        $appointment = DB::transaction(function () use ($request, $subject) {
            $slot = Slot::where('id', $request->slot_id)
                ->where('status', 'available')
                ->lockForUpdate()
                ->first();

            if (!$slot) {
                return null;
            }

            $slot->update(['status' => 'booked']);

            return Appointment::create([
                'user_id' => Auth::id(),
                'subject_id' => $request->subject_id,
                'doubt_id' => $request->doubt_id,
                'slot_id' => $slot->id,
                'appointment_date' => $slot->date,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'duration' => $subject->session_duration ?? (int) \App\Models\Setting::get('session_duration', 50),
                'status' => 'pending',
            ]);
        });

        if (!$appointment) {
            return back()->withErrors(['slot_id' => 'This slot is no longer available.']);
        }

        try {
            $automation->sendAppointmentEmail($appointment->load(['student', 'subject', 'doubt']), 'appointment_created');
            $automation->createInternalAdminNotification(
                'Booking',
                'New Session Booked',
                Auth::user()->name . " booked a session for {$subject->name}.",
                $appointment,
                Auth::user(),
                route('admin.appointments.show', $appointment->id),
                'calendar'
            );
        } catch (\Throwable) {
        }

        $logger->log('Booking', 'Create', "Student '" . Auth::user()->name . "' created a booking.", null, $appointment->toArray());

        return redirect()->route('student.payment.pay', $appointment->id)->with('success', 'Booking created! Please complete the payment to confirm your session.');
    }

    public function index()
    {
        $bookings = Appointment::where('user_id', Auth::id())
            ->with(['subject', 'doubt', 'slot', 'payment.invoice'])
            ->latest()
            ->paginate(10);

        return view('student.booking.index', compact('bookings'));
    }

    public function upcoming()
    {
        $sessions = Appointment::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'scheduled', 'confirmed', 'rescheduled'])
            ->where('appointment_date', '>=', now()->toDateString())
            ->with(['subject', 'doubt', 'slot'])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get();

        return view('student.sessions.upcoming', compact('sessions'));
    }

    public function past()
    {
        $sessions = Appointment::where('user_id', Auth::id())
            ->where(function($query) {
                $query->where('status', 'completed')
                      ->orWhere('appointment_date', '<', now()->toDateString());
            })
            ->with(['subject', 'doubt', 'slot'])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        return view('student.sessions.past', compact('sessions'));
    }
}
