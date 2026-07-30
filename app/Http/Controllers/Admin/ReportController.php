<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $range = $request->get('range', '30_days');
        
        $startDate = match ($range) {
            'today' => Carbon::today(),
            'this_week' => Carbon::now()->startOfWeek(),
            'this_month' => Carbon::now()->startOfMonth(),
            'custom' => $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays(30),
            default => Carbon::now()->subDays(30),
        };
        
        $endDate = match ($range) {
            'custom' => $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::now()->endOfDay(),
            default => Carbon::now()->endOfDay(),
        };

        // Basic Stats
        $totalRevenue = Payment::where('payment_status', 'successful')->whereBetween('created_at', [$startDate, $endDate])->sum('amount');
        $totalBookings = Appointment::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalStudents = User::where('role', 'student')->whereBetween('created_at', [$startDate, $endDate])->count();
        $totalRefunds = Refund::whereBetween('created_at', [$startDate, $endDate])->sum('refund_amount');

        // Charts Data
        // 1. Revenue Trend (Daily)
        $revenueTrend = Payment::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount) as total'))
            ->where('payment_status', 'successful')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 2. Booking Trend (Daily)
        $bookingTrend = Appointment::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(id) as total'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // 3. Payment Status Distribution
        $paymentStatus = Payment::select('payment_status', DB::raw('COUNT(id) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('payment_status')
            ->get();

        // 4. Session Status Distribution
        $sessionStatus = Appointment::select('status', DB::raw('COUNT(id) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('status')
            ->get();

        // 5. Subject Popularity
        $subjectPopularity = Appointment::select('subjects.name', DB::raw('COUNT(appointments.id) as count'))
            ->join('subjects', 'subjects.id', '=', 'appointments.subject_id')
            ->whereBetween('appointments.created_at', [$startDate, $endDate])
            ->groupBy('subjects.name')
            ->orderBy('count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'range', 'startDate', 'endDate',
            'totalRevenue', 'totalBookings', 'totalStudents', 'totalRefunds',
            'revenueTrend', 'bookingTrend', 'paymentStatus', 'sessionStatus', 'subjectPopularity'
        ));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'bookings');
        $range = $request->get('range', '30_days');
        
        $startDate = match ($range) {
            'today' => Carbon::today(),
            'this_week' => Carbon::now()->startOfWeek(),
            'this_month' => Carbon::now()->startOfMonth(),
            'custom' => $request->get('start_date') ? Carbon::parse($request->get('start_date')) : Carbon::now()->subDays(30),
            default => Carbon::now()->subDays(30),
        };
        
        $endDate = match ($range) {
            'custom' => $request->get('end_date') ? Carbon::parse($request->get('end_date'))->endOfDay() : Carbon::now()->endOfDay(),
            default => Carbon::now()->endOfDay(),
        };

        $fileName = "{$type}_report_{$startDate->format('Ymd')}_{$endDate->format('Ymd')}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($type, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            if ($type === 'bookings') {
                fputcsv($file, ['ID', 'Student', 'Subject', 'Date', 'Time', 'Status', 'Created At']);
                $data = Appointment::with(['student', 'subject'])->whereBetween('created_at', [$startDate, $endDate])->get();
                foreach ($data as $row) {
                    fputcsv($file, [$row->id, $row->student->name ?? 'N/A', $row->subject->name ?? 'N/A', $row->appointment_date?->format('Y-m-d'), $row->start_time, $row->status, $row->created_at->format('Y-m-d H:i')]);
                }
            } elseif ($type === 'payments') {
                fputcsv($file, ['ID', 'Student', 'Amount', 'Currency', 'Status', 'Transaction ID', 'Created At']);
                $data = Payment::with(['student'])->whereBetween('created_at', [$startDate, $endDate])->get();
                foreach ($data as $row) {
                    fputcsv($file, [$row->id, $row->student->name ?? 'N/A', $row->amount, $row->currency, $row->payment_status, $row->transaction_id, $row->created_at->format('Y-m-d H:i')]);
                }
            } elseif ($type === 'refunds') {
                fputcsv($file, ['ID', 'Student', 'Amount', 'Status', 'Reason', 'Admin Notes', 'Created At']);
                $data = Refund::with(['student'])->whereBetween('created_at', [$startDate, $endDate])->get();
                foreach ($data as $row) {
                    fputcsv($file, [$row->id, $row->student->name ?? 'N/A', $row->refund_amount, $row->status, $row->reason, $row->admin_notes, $row->created_at->format('Y-m-d H:i')]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
