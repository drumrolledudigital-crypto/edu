<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            ['key' => 'booking_confirmation', 'name' => 'Booking Confirmation', 'subject' => 'Your session booking is confirmed', 'body' => "Hello {{student_name}},\n\nYour {{subject}} session has been booked for {{session_date}} at {{session_time}}.\n\nMeeting Link: {{meet_link}}\n\nThank you."],
            ['key' => 'session_reminder', 'name' => 'Session Reminder', 'subject' => 'Reminder: Your session starts soon', 'body' => "Hello {{student_name}},\n\nThis is a reminder for your {{subject}} session on {{session_date}} at {{session_time}}.\n\nJoin: {{meet_link}}"],
            ['key' => 'reschedule_notice', 'name' => 'Reschedule Notice', 'subject' => 'Your session has been rescheduled', 'body' => "Hello {{student_name}},\n\nYour {{subject}} session has been rescheduled to {{session_date}} at {{session_time}}.\n\nJoin: {{meet_link}}"],
            ['key' => 'payment_success', 'name' => 'Payment Success', 'subject' => 'Payment received successfully', 'body' => "Hello {{student_name}},\n\nWe received your payment successfully. Thank you."],
            ['key' => 'payment_failed', 'name' => 'Payment Failed', 'subject' => 'Payment failed', 'body' => "Hello {{student_name}},\n\nYour payment could not be processed. Please try again or contact support."],
            ['key' => 'invoice_generated', 'name' => 'Invoice Generated', 'subject' => 'Your invoice is ready', 'body' => "Hello {{student_name}},\n\nYour invoice has been generated and is available in your account."],
            ['key' => 'refund_approved', 'name' => 'Refund Approved', 'subject' => 'Your refund has been approved', 'body' => "Hello {{student_name}},\n\nYour refund request has been approved and will be processed shortly."],
            ['key' => 'refund_rejected', 'name' => 'Refund Rejected', 'subject' => 'Refund request update', 'body' => "Hello {{student_name}},\n\nYour refund request was not approved. Please contact support for details."],
        ];

        foreach ($templates as $template) {
            NotificationTemplate::updateOrCreate(['key' => $template['key']], $template);
        }
    }
}
