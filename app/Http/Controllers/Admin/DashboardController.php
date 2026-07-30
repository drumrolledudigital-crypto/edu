<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Subject;
use App\Models\Doubt;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\BookPurchase;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin.dashboard.stats', 60, function () {
            return [
                'totalStudents' => User::where('role', 'student')->count(),
                'totalSubjects' => Subject::count(),
                'totalDoubts' => Doubt::count(),
                'totalBookings' => Appointment::count(),
                'upcomingSessions' => Appointment::whereIn('status', ['pending', 'scheduled', 'confirmed', 'rescheduled'])->count(),
                'completedSessions' => Appointment::where('status', 'completed')->count(),
                'totalPayments' => Payment::count(),
                'successfulPayments' => Payment::where('payment_status', 'successful')->count(),
                'pendingPayments' => Payment::where('payment_status', 'pending')->count(),
                'refundedPayments' => Payment::where('payment_status', 'refunded')->count(),
                'totalRevenue' => Payment::where('payment_status', 'successful')->sum('amount'),
                'totalBookPurchases' => BookPurchase::where('status', 'completed')->count(),
                'totalBookRevenue' => BookPurchase::where('status', 'completed')->sum('amount'),
                'pendingRefunds' => Refund::where('status', 'pending')->count(),
            ];
        });

        $totalStudents = $stats['totalStudents'];
        $totalSubjects = $stats['totalSubjects'];
        $totalDoubts = $stats['totalDoubts'];
        $totalBookings = $stats['totalBookings'];
        $upcomingSessions = $stats['upcomingSessions'];
        $completedSessions = $stats['completedSessions'];
        $totalPayments = $stats['totalPayments'];
        $successfulPayments = $stats['successfulPayments'];
        $pendingPayments = $stats['pendingPayments'];
        $refundedPayments = $stats['refundedPayments'];
        $totalRevenue = $stats['totalRevenue'];
        $pendingRefunds = $stats['pendingRefunds'];

        // Recent Activity
        $recentStudents = User::where('role', 'student')->latest()->take(5)->get();
        $recentBookings = Appointment::with(['student', 'subject'])->latest()->take(5)->get();
        $recentPayments = Payment::with(['student', 'appointment.subject'])->latest()->take(5)->get();
        $recentRefunds = Refund::with(['student', 'payment'])->latest()->take(5)->get();
        $recentDoubts = Doubt::with(['student', 'subject'])->latest()->take(5)->get();
        
        $recentNotifications = \App\Models\AdminNotification::latest()->take(5)->get();
        $unreadNotificationsCount = \App\Models\AdminNotification::where('status', 'unread')->count();

        $recentActivities = \App\Models\AuditLog::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalStudents', 'totalSubjects', 'totalDoubts', 'totalBookings',
            'upcomingSessions', 'completedSessions',
            'totalPayments', 'successfulPayments', 'pendingPayments', 'refundedPayments',
            'totalRevenue', 'pendingRefunds',
            'recentStudents', 'recentBookings', 'recentPayments', 'recentRefunds', 'recentDoubts',
            'recentNotifications', 'unreadNotificationsCount', 'recentActivities'
        ));
    }
}
