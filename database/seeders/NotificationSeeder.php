<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Notifications\AdminNotification;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        if (!$admin) return;

        $notifications = [
            [
                'type' => 'registration',
                'title' => 'New Student Registered',
                'message' => 'Rahul Sharma has just joined the platform.',
                'action_url' => '/admin/students',
                'action_text' => 'View Student'
            ],
            [
                'type' => 'booking',
                'title' => 'New Session Booked',
                'message' => 'Anjali Singh booked a Mathematics session for tomorrow.',
                'action_url' => '/admin/appointments',
                'action_text' => 'View Appointment'
            ],
            [
                'type' => 'payment',
                'title' => 'Payment Received',
                'message' => 'Received $50.00 from Ishaan Verma for Invoice #INV-001.',
                'action_url' => '/admin/payments',
                'action_text' => 'View Payment'
            ]
        ];

        foreach ($notifications as $data) {
            $admin->notify(new AdminNotification($data));
        }
    }
}
