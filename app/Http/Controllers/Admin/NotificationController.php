<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Services\IntegrationAutomationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display the email notification history.
     */
    public function index()
    {
        return view('admin.notifications.index');
    }

    /**
     * Get the email logs for the DataTable.
     */
    public function list()
    {
        $logs = EmailLog::with('appointment.student', 'appointment.subject')
            ->latest()
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'recipient' => $log->recipient,
                    'subject' => $log->subject,
                    'type' => $log->type,
                    'status' => $log->status,
                    'retry_count' => $log->retry_count,
                    'sent_at' => $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                    'student_name' => $log->appointment?->student?->name ?? 'N/A',
                    'appointment_id' => $log->appointment_id,
                ];
            });

        return response()->json(['data' => $logs]);
    }

    /**
     * Show details of a specific email log.
     */
    public function show($id)
    {
        $log = EmailLog::with('appointment.student', 'appointment.subject', 'appointment.doubt')->findOrFail($id);
        
        return view('admin.notifications.show', compact('log'));
    }

    /**
     * Resend a failed email.
     */
    public function resend($id, IntegrationAutomationService $automation)
    {
        $log = EmailLog::findOrFail($id);
        
        // This is a simplified resend logic
        // In a real app, you might want to reconstruct the body or use the stored one
        // For now, we'll try to re-trigger based on type if it's an appointment email
        
        try {
            if ($log->appointment_id && $log->appointment) {
                $automation->sendAppointmentEmail($log->appointment, $log->type);
            } else {
                // Handle non-appointment emails (like welcome or test)
                if ($log->type === 'welcome_student' && $log->recipient) {
                    $user = \App\Models\User::where('email', $log->recipient)->first();
                    if ($user) {
                        $automation->sendWelcomeEmail($user);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Email resend triggered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to resend email. Please try again later.'
            ], 500);
        }
    }
}
