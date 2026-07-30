<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doubt;
use App\Models\Payment;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $studentId = Auth::id();

        $counts = [
            'upcoming' => Appointment::where('user_id', $studentId)->whereIn('status', ['pending', 'scheduled', 'confirmed', 'rescheduled'])->where('appointment_date', '>=', now()->toDateString())->count(),
            'completed' => Appointment::where('user_id', $studentId)->where('status', 'completed')->count(),
            'doubts' => Doubt::where('user_id', $studentId)->count(),
        ];

        $recentPayments = Payment::where('student_id', $studentId)->with('appointment.subject')->latest()->take(5)->get();
        
        $recentNotifications = EmailLog::where('recipient', $user->email)
            ->where('status', 'sent')
            ->latest()
            ->take(5)
            ->get();

        return view('student.dashboard', compact('user', 'counts', 'recentPayments', 'recentNotifications'));
    }
}
