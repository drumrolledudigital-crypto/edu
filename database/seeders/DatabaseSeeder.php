<?php

namespace Database\Seeders;

use App\Models\AdminNotification;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Doubt;
use App\Models\EmailLog;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Setting;
use App\Models\Slot;
use App\Models\Subject;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RBACSeeder::class,
            SettingSeeder::class,
            NotificationTemplateSeeder::class,
            BookSeeder::class,
        ]);

        $this->seedStudents();
        $this->seedSubjects();
        $this->seedSlots();
        $this->seedDoubts();
        $this->seedAppointments();
        $this->seedPayments();
        $this->seedInvoices();
        $this->seedRefunds();
        $this->seedEmailLogs();
        $this->seedAdminNotifications();
        $this->seedAuditLogs();
    }

    private function seedStudents(): void
    {
        $students = [
            ['name' => 'Rahul Sharma', 'email' => 'rahul.sharma@student.com', 'parent_name' => 'Vikram Sharma', 'mobile_number' => '9876543210', 'student_class' => '5', 'is_active' => true, 'email_verified_at' => now()->subDays(30)],
            ['name' => 'Priya Patel', 'email' => 'priya.patel@student.com', 'parent_name' => 'Mahesh Patel', 'mobile_number' => '9876543211', 'student_class' => '6', 'is_active' => true, 'email_verified_at' => now()->subDays(25)],
            ['name' => 'Anjali Singh', 'email' => 'anjali.singh@student.com', 'parent_name' => 'Rajesh Singh', 'mobile_number' => '9876543212', 'student_class' => '7', 'is_active' => true, 'email_verified_at' => now()->subDays(20)],
            ['name' => 'Aarav Kumar', 'email' => 'aarav.kumar@student.com', 'parent_name' => 'Suresh Kumar', 'mobile_number' => '9876543213', 'student_class' => '4', 'is_active' => true, 'email_verified_at' => now()->subDays(15)],
            ['name' => 'Diya Gupta', 'email' => 'diya.gupta@student.com', 'parent_name' => 'Ashok Gupta', 'mobile_number' => '9876543214', 'student_class' => '3', 'is_active' => true, 'email_verified_at' => now()->subDays(10)],
            ['name' => 'Ishaan Verma', 'email' => 'ishaan.verma@student.com', 'parent_name' => 'Manoj Verma', 'mobile_number' => '9876543215', 'student_class' => '8', 'is_active' => false, 'email_verified_at' => now()->subDays(40)],
            ['name' => 'Sneha Reddy', 'email' => 'sneha.reddy@student.com', 'parent_name' => 'Prasad Reddy', 'mobile_number' => '9876543216', 'student_class' => '6', 'is_active' => true, 'email_verified_at' => null],
            ['name' => 'Kabir Joshi', 'email' => 'kabir.joshi@student.com', 'parent_name' => 'Deepak Joshi', 'mobile_number' => '9876543217', 'student_class' => '5', 'is_active' => true, 'email_verified_at' => now()->subDays(5)],
            ['name' => 'Meera Nair', 'email' => 'meera.nair@student.com', 'parent_name' => 'Rajan Nair', 'mobile_number' => '9876543218', 'student_class' => '7', 'is_active' => true, 'email_verified_at' => now()->subDays(3)],
            ['name' => 'Arjun Mehta', 'email' => 'arjun.mehta@student.com', 'parent_name' => 'Vikram Mehta', 'mobile_number' => '9876543219', 'student_class' => '2', 'is_active' => false, 'email_verified_at' => now()->subDays(50)],
            ['name' => 'Nisha Agarwal', 'email' => 'nisha.agarwal@student.com', 'parent_name' => 'Rakesh Agarwal', 'mobile_number' => '9876543220', 'student_class' => '4', 'is_active' => true, 'email_verified_at' => now()->subDays(2)],
            ['name' => 'Rohan Deshmukh', 'email' => 'rohan.deshmukh@student.com', 'parent_name' => 'Kiran Deshmukh', 'mobile_number' => '9876543221', 'student_class' => '8', 'is_active' => true, 'email_verified_at' => now()->subDays(1)],
        ];

        $index = 0;
        foreach ($students as $data) {
            $index++;
            User::updateOrCreate(
                ['email' => $data['email']],
                array_merge($data, [
                    'password' => Hash::make('student123'),
                    'role' => 'student',
                    'roll_number' => 'DRM' . str_pad($index, 3, '0', STR_PAD_LEFT),
                ])
            );
        }
    }

    private function seedSubjects(): void
    {
        $subjects = [
            ['name' => 'Mathematics', 'description' => 'Algebra, Geometry, Arithmetic, and problem-solving for Classes 1-8.', 'class_range_from' => 1, 'class_range_to' => 8, 'session_duration' => 50, 'sort_order' => 1, 'icon' => 'calculator', 'status' => 'active'],
            ['name' => 'Science', 'description' => 'Physics, Chemistry, and Biology fundamentals for young learners.', 'class_range_from' => 3, 'class_range_to' => 8, 'session_duration' => 50, 'sort_order' => 2, 'icon' => 'flask', 'status' => 'active'],
            ['name' => 'English', 'description' => 'Grammar, vocabulary, reading comprehension, and creative writing.', 'class_range_from' => 1, 'class_range_to' => 8, 'session_duration' => 50, 'sort_order' => 3, 'icon' => 'book-open', 'status' => 'active'],
            ['name' => 'Hindi', 'description' => 'Hindi grammar, comprehension, essay writing, and poetry.', 'class_range_from' => 1, 'class_range_to' => 8, 'session_duration' => 50, 'sort_order' => 4, 'icon' => 'languages', 'status' => 'active'],
            ['name' => 'Social Studies', 'description' => 'History, Geography, Civics, and Environmental Studies.', 'class_range_from' => 4, 'class_range_to' => 8, 'session_duration' => 50, 'sort_order' => 5, 'icon' => 'globe', 'status' => 'active'],
            ['name' => 'Computer Science', 'description' => 'Basic programming, digital literacy, and computational thinking.', 'class_range_from' => 3, 'class_range_to' => 8, 'session_duration' => 50, 'sort_order' => 6, 'icon' => 'monitor', 'status' => 'active'],
            ['name' => 'Mental Ability', 'description' => 'Logical reasoning, pattern recognition, and olympiad preparation.', 'class_range_from' => 2, 'class_range_to' => 8, 'session_duration' => 50, 'sort_order' => 7, 'icon' => 'brain', 'status' => 'active'],
            ['name' => 'Sanskrit', 'description' => 'Sanskrit language basics, shlokas, and grammar for school curriculum.', 'class_range_from' => 5, 'class_range_to' => 8, 'session_duration' => 50, 'sort_order' => 8, 'icon' => 'scroll', 'status' => 'disabled'],
        ];

        foreach ($subjects as $data) {
            Subject::updateOrCreate(['name' => $data['name']], $data);
        }
    }

    private function seedSlots(): void
    {
        $today = Carbon::today();

        for ($day = -14; $day <= 21; $day++) {
            $date = $today->copy()->addDays($day);
            $times = [
                ['start' => '09:00', 'end' => '09:50'],
                ['start' => '10:00', 'end' => '10:50'],
                ['start' => '11:00', 'end' => '11:50'],
                ['start' => '14:00', 'end' => '14:50'],
                ['start' => '15:00', 'end' => '15:50'],
                ['start' => '16:00', 'end' => '16:50'],
                ['start' => '17:00', 'end' => '17:50'],
                ['start' => '18:00', 'end' => '18:50'],
            ];

            foreach ($times as $time) {
                $status = 'available';
                if ($day < 0) {
                    $status = $day % 2 === 0 ? 'available' : 'booked';
                } elseif ($day === 0) {
                    $status = 'available';
                } else {
                    $status = $day % 3 === 0 ? 'inactive' : 'available';
                }

                Slot::updateOrCreate(
                    ['date' => $date->format('Y-m-d'), 'start_time' => $time['start'], 'end_time' => $time['end']],
                    ['status' => $status]
                );
            }
        }
    }

    private function seedDoubts(): void
    {
        $students = User::where('role', 'student')->get();
        $subjects = Subject::where('status', 'active')->get();

        $doubtTopics = [
            ['topic' => 'Algebra', 'title' => 'Need help with linear equations', 'description' => 'I am struggling to solve equations with variables on both sides. Can you explain the step-by-step method?'],
            ['topic' => 'Geometry', 'title' => 'Understanding circle theorems', 'description' => 'The angle subtended by an arc at the centre is double the angle at the remaining part. I need examples.'],
            ['topic' => 'Fractions', 'title' => 'Adding and subtracting fractions', 'description' => 'How do I add fractions with different denominators? Please explain with examples.'],
            ['topic' => 'Photosynthesis', 'title' => 'How plants make food', 'description' => 'Explain the process of photosynthesis with a diagram. What role does sunlight play?'],
            ['topic' => 'Grammar', 'title' => 'Active and passive voice', 'description' => 'I keep confusing active and passive voice. Can you give me a clear formula to convert between them?'],
            ['topic' => 'Water Cycle', 'title' => 'Evaporation and condensation', 'description' => 'Explain the water cycle with all stages. How does water return to earth from clouds?'],
            ['topic' => 'Numbers', 'title' => 'HCF and LCM', 'description' => 'What is the difference between HCF and LCM? Show me the prime factorization method.'],
            ['topic' => 'Writing', 'title' => 'Essay on environmental pollution', 'description' => 'I need to write a 200-word essay on environmental pollution for my English exam. Please help.'],
            ['topic' => 'Physics', 'title' => 'Laws of motion', 'description' => 'Explain Newtons three laws of motion with real-life examples for a Class 7 student.'],
            ['topic' => 'Chemistry', 'title' => 'Elements and compounds', 'description' => 'What is the difference between elements and compounds? Give 5 examples of each.'],
            ['topic' => 'History', 'title' => 'Indian freedom movement', 'description' => 'Give me a timeline of important events in the Indian freedom struggle from 1857 to 1947.'],
            ['topic' => 'Geography', 'title' => 'Solar system planets', 'description' => 'List all planets in order from the sun with one interesting fact about each planet.'],
            ['topic' => 'Programming', 'title' => 'Introduction to Scratch', 'description' => 'How do I create a simple animation in Scratch? I am a beginner and need step-by-step guidance.'],
            ['topic' => 'Reasoning', 'title' => 'Number series patterns', 'description' => 'Help me understand how to find the next number in a series like 2, 6, 12, 20, 30, __'],
            ['topic' => 'Ratio', 'title' => 'Ratio and proportion problems', 'description' => 'I do not understand how to solve mixture problems using ratio and proportion. Please explain.'],
        ];

        $statuses = ['pending', 'pending', 'accepted', 'accepted', 'resolved', 'resolved', 'resolved', 'cancelled'];

        foreach ($doubtTopics as $index => $doubt) {
            $student = $students[$index % $students->count()];
            $subject = $subjects[$index % $subjects->count()];
            $status = $statuses[$index % count($statuses)];

            Doubt::updateOrCreate(
                ['user_id' => $student->id, 'title' => json_encode($doubt['title'])],
                [
                    'subject_id' => $subject->id,
                    'topic_name' => $doubt['topic'],
                    'title' => $doubt['title'],
                    'description' => $doubt['description'],
                    'attachment' => null,
                    'status' => $status,
                ]
            );
        }
    }

    private function seedAppointments(): void
    {
        $students = User::where('role', 'student')->get();
        $subjects = Subject::where('status', 'active')->get();
        $bookedSlots = Slot::where('status', 'booked')->get();
        $availableSlots = Slot::where('status', 'available')->get();
        $doubts = Doubt::all();

        $allSlots = $availableSlots->merge($bookedSlots);
        $today = Carbon::today();

        $appointmentConfigs = [
            ['status' => 'completed', 'days_offset' => -10, 'meeting_status' => 'completed', 'calendar_status' => 'synced'],
            ['status' => 'completed', 'days_offset' => -8, 'meeting_status' => 'completed', 'calendar_status' => 'synced'],
            ['status' => 'completed', 'days_offset' => -5, 'meeting_status' => 'completed', 'calendar_status' => 'synced'],
            ['status' => 'completed', 'days_offset' => -3, 'meeting_status' => 'completed', 'calendar_status' => 'synced'],
            ['status' => 'completed', 'days_offset' => -1, 'meeting_status' => 'completed', 'calendar_status' => 'synced'],
            ['status' => 'cancelled', 'days_offset' => -7, 'meeting_status' => 'pending', 'calendar_status' => 'pending'],
            ['status' => 'cancelled', 'days_offset' => -4, 'meeting_status' => 'pending', 'calendar_status' => 'pending'],
            ['status' => 'confirmed', 'days_offset' => 0, 'meeting_status' => 'pending', 'calendar_status' => 'pending'],
            ['status' => 'confirmed', 'days_offset' => 1, 'meeting_status' => 'pending', 'calendar_status' => 'pending'],
            ['status' => 'scheduled', 'days_offset' => 2, 'meeting_status' => 'pending', 'calendar_status' => 'pending'],
            ['status' => 'scheduled', 'days_offset' => 5, 'meeting_status' => 'pending', 'calendar_status' => 'pending'],
            ['status' => 'scheduled', 'days_offset' => 7, 'meeting_status' => 'pending', 'calendar_status' => 'pending'],
            ['status' => 'pending', 'days_offset' => 3, 'meeting_status' => 'pending', 'calendar_status' => 'pending'],
            ['status' => 'pending', 'days_offset' => 4, 'meeting_status' => 'pending', 'calendar_status' => 'pending'],
            ['status' => 'rescheduled', 'days_offset' => -2, 'meeting_status' => 'cancelled', 'calendar_status' => 'cancelled'],
        ];

        foreach ($appointmentConfigs as $index => $config) {
            $student = $students[$index % $students->count()];
            $subject = $subjects[$index % $subjects->count()];
            $doubt = $doubts->where('user_id', $student->id)->first() ?? $doubts[$index % $doubts->count()];
            $slot = $allSlots->where('date', $today->copy()->addDays($config['days_offset'])->format('Y-m-d'))->first()
                ?? $allSlots[$index % $allSlots->count()];

            $appointmentDate = $today->copy()->addDays($config['days_offset']);

            $meetLink = null;
            $meetEventId = null;
            if (in_array($config['status'], ['completed', 'confirmed', 'scheduled'])) {
                $meetLink = 'https://meet.google.com/' . Str::random(3) . '-' . Str::random(4) . '-' . Str::random(3);
                $meetEventId = 'meet_' . Str::random(16);
            }

            $calendarEventId = null;
            if ($config['calendar_status'] === 'synced') {
                $calendarEventId = 'cal_' . Str::random(20);
            }

            Appointment::updateOrCreate(
                ['user_id' => $student->id, 'slot_id' => $slot->id, 'appointment_date' => $appointmentDate->format('Y-m-d')],
                [
                    'subject_id' => $subject->id,
                    'doubt_id' => $doubt->id,
                    'start_time' => $slot->start_time,
                    'end_time' => $slot->end_time,
                    'duration' => $subject->session_duration,
                    'status' => $config['status'],
                    'meet_link' => $meetLink,
                    'meet_event_id' => $meetEventId,
                    'meet_status' => $config['status'] === 'completed' ? 'completed' : ($meetLink ? 'created' : 'pending'),
                    'meet_generated_at' => $meetLink ? $appointmentDate->copy()->subDay()->toDateTimeString() : null,
                    'google_meet_link' => $meetLink,
                    'google_meet_id' => $meetEventId,
                    'meeting_status' => $config['meeting_status'],
                    'meeting_generated_at' => $meetLink ? $appointmentDate->copy()->subDay()->toDateTimeString() : null,
                    'google_calendar_event_id' => $calendarEventId,
                    'calendar_status' => $config['calendar_status'],
                    'calendar_created_at' => $calendarEventId ? $appointmentDate->copy()->subDay()->toDateTimeString() : null,
                    'calendar_event_id' => $calendarEventId,
                    'calendar_sync_status' => $config['calendar_status'] === 'synced' ? 'synced' : 'pending',
                    'calendar_last_synced_at' => $config['calendar_status'] === 'synced' ? $appointmentDate->copy()->subHours(2)->toDateTimeString() : null,
                    'email_notification_status' => in_array($config['status'], ['confirmed', 'completed']) ? 'sent' : 'pending',
                    'email_notification_sent_at' => in_array($config['status'], ['confirmed', 'completed']) ? $appointmentDate->copy()->subDay()->toDateTimeString() : null,
                    'admin_notes' => $config['status'] === 'cancelled' ? 'Student requested cancellation due to schedule conflict.' : null,
                    'rescheduled_at' => $config['status'] === 'rescheduled' ? $appointmentDate->copy()->subDays(3)->toDateTimeString() : null,
                ]
            );
        }
    }

    private function seedPayments(): void
    {
        $students = User::where('role', 'student')->get();
        $appointments = Appointment::all();
        $sessionPrice = (float) Setting::get('session_price', 32.00);

        $paymentConfigs = [
            ['payment_status' => 'successful', 'days_ago' => 10],
            ['payment_status' => 'successful', 'days_ago' => 8],
            ['payment_status' => 'successful', 'days_ago' => 5],
            ['payment_status' => 'successful', 'days_ago' => 3],
            ['payment_status' => 'successful', 'days_ago' => 1],
            ['payment_status' => 'failed', 'days_ago' => 7],
            ['payment_status' => 'failed', 'days_ago' => 4],
            ['payment_status' => 'successful', 'days_ago' => 0],
            ['payment_status' => 'successful', 'days_ago' => -1],
            ['payment_status' => 'pending', 'days_ago' => 0],
            ['payment_status' => 'pending', 'days_ago' => 0],
            ['payment_status' => 'successful', 'days_ago' => -2],
            ['payment_status' => 'successful', 'days_ago' => -5],
            ['payment_status' => 'refunded', 'days_ago' => -3],
            ['payment_status' => 'refunded', 'days_ago' => -7],
        ];

        foreach ($paymentConfigs as $index => $config) {
            $student = $students[$index % $students->count()];
            $appointment = $appointments[$index % $appointments->count()];
            $amount = $sessionPrice + ($index % 3 === 0 ? 10 : 0);
            $paymentDate = Carbon::now()->subDays($config['days_ago']);

            $stripeId = 'pi_seed_' . ($index + 1);
            $transactionId = 'txn_seed_' . ($index + 1);

            Payment::updateOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'student_id' => $student->id,
                    'stripe_payment_id' => $stripeId,
                    'transaction_id' => $transactionId,
                    'amount' => $amount,
                    'currency' => 'USD',
                    'payment_status' => $config['payment_status'],
                    'payment_date' => $paymentDate->toDateTimeString(),
                ]
            );
        }
    }

    private function seedInvoices(): void
    {
        $payments = Payment::where('payment_status', 'successful')->get();
        $students = User::where('role', 'student')->get();
        $appointments = Appointment::all();

        $prefix = Setting::get('invoice_prefix', 'INV');
        $startingNumber = (int) Setting::get('invoice_starting_number', 1001);

        foreach ($payments as $index => $payment) {
            $invoiceNumber = $prefix . '-' . str_pad($startingNumber + $index, 4, '0', STR_PAD_LEFT);
            $student = $students->where('id', $payment->student_id)->first() ?? $students->first();
            $appointment = $appointments->where('id', $payment->appointment_id)->first() ?? $appointments->first();

            Invoice::updateOrCreate(
                ['payment_id' => $payment->id],
                [
                    'invoice_number' => $invoiceNumber,
                    'student_id' => $student->id,
                    'appointment_id' => $appointment?->id,
                    'amount' => $payment->amount,
                    'currency' => 'USD',
                    'status' => 'generated',
                    'invoice_date' => $payment->payment_date ? Carbon::parse($payment->payment_date)->format('Y-m-d') : Carbon::today()->format('Y-m-d'),
                    'pdf_path' => 'invoices/' . $invoiceNumber . '.pdf',
                ]
            );
        }
    }

    private function seedRefunds(): void
    {
        $refundedPayments = Payment::where('payment_status', 'refunded')->get();
        $students = User::where('role', 'student')->get();
        $appointments = Appointment::all();
        $invoices = Invoice::all();

        foreach ($refundedPayments as $index => $payment) {
            $student = $students->where('id', $payment->student_id)->first() ?? $students->first();
            $appointment = $appointments->where('id', $payment->appointment_id)->first();
            $invoice = $invoices->where('payment_id', $payment->id)->first();

            $statuses = ['approved', 'refunded'];
            $status = $statuses[$index % count($statuses)];

            Refund::updateOrCreate(
                ['payment_id' => $payment->id],
                [
                    'student_id' => $student->id,
                    'invoice_id' => $invoice?->id,
                    'appointment_id' => $appointment?->id,
                    'amount' => $payment->amount,
                    'refund_amount' => $payment->amount,
                    'reason' => 'Session was cancelled by student due to personal emergency.',
                    'status' => $status,
                    'refund_date' => $status === 'refunded' ? Carbon::now()->subDays(2)->format('Y-m-d') : null,
                    'admin_notes' => 'Refund processed as per policy. Student had valid reason.',
                ]
            );
        }

        $pendingRefunds = [
            ['payment_index' => 0, 'reason' => 'Dissatisfied with the session quality. Requesting full refund.'],
            ['payment_index' => 1, 'reason' => 'Technical issues prevented attending the session. Need refund.'],
        ];

        $allPayments = Payment::where('payment_status', 'successful')->get();
        foreach ($pendingRefunds as $pending) {
            if ($allPayments->has($pending['payment_index'])) {
                $payment = $allPayments[$pending['payment_index']];
                $student = $students->where('id', $payment->student_id)->first() ?? $students->first();

                Refund::updateOrCreate(
                    ['payment_id' => $payment->id, 'status' => 'pending'],
                    [
                        'student_id' => $student->id,
                        'invoice_id' => null,
                        'appointment_id' => $payment->appointment_id,
                        'amount' => $payment->amount,
                        'refund_amount' => $payment->amount,
                        'reason' => $pending['reason'],
                        'refund_date' => null,
                        'admin_notes' => null,
                    ]
                );
            }
        }
    }

    private function seedEmailLogs(): void
    {
        $appointments = Appointment::all();
        $students = User::where('role', 'student')->get();

        $emailTypes = [
            ['type' => 'booking_confirmation', 'subject' => 'Your session booking is confirmed', 'status' => 'sent'],
            ['type' => 'session_reminder', 'subject' => 'Reminder: Your session starts soon', 'status' => 'sent'],
            ['type' => 'payment_success', 'subject' => 'Payment received successfully', 'status' => 'sent'],
            ['type' => 'payment_failed', 'subject' => 'Payment failed', 'status' => 'failed'],
            ['type' => 'invoice_generated', 'subject' => 'Your invoice is ready', 'status' => 'sent'],
            ['type' => 'booking_confirmation', 'subject' => 'Your session booking is confirmed', 'status' => 'sent'],
            ['type' => 'session_reminder', 'subject' => 'Reminder: Your session starts soon', 'status' => 'queued'],
            ['type' => 'reschedule_notice', 'subject' => 'Your session has been rescheduled', 'status' => 'sent'],
            ['type' => 'refund_approved', 'subject' => 'Your refund has been approved', 'status' => 'sent'],
            ['type' => 'booking_confirmation', 'subject' => 'Your session booking is confirmed', 'status' => 'sent'],
        ];

        foreach ($emailTypes as $index => $email) {
            $appointment = $appointments[$index % $appointments->count()];
            $student = $students[$index % $students->count()];

            $sentAt = null;
            $errorMessage = null;
            if ($email['status'] === 'sent') {
                $sentAt = Carbon::now()->subDays(rand(0, 10))->toDateTimeString();
            } elseif ($email['status'] === 'failed') {
                $errorMessage = 'SMTP connection timeout after 30 seconds.';
            }

            EmailLog::create([
                'recipient' => $student->email,
                'subject' => $email['subject'],
                'type' => $email['type'],
                'status' => $email['status'],
                'retry_count' => $email['status'] === 'failed' ? 3 : 0,
                'error_message' => $errorMessage,
                'appointment_id' => $appointment->id,
                'sent_at' => $sentAt,
            ]);
        }
    }

    private function seedAdminNotifications(): void
    {
        $admin = User::where('role', 'admin')->first();
        if (!$admin) {
            $admin = User::where('email', 'super@gmail.com')->first();
        }
        if (!$admin) return;

        $notifications = [
            ['type' => 'registration', 'title' => 'New Student Registered', 'message' => 'Rahul Sharma has just joined the platform.', 'status' => 'read', 'icon' => 'fas fa-user-plus', 'url' => '/admin/students'],
            ['type' => 'booking', 'title' => 'New Session Booked', 'message' => 'Anjali Singh booked a Mathematics session for tomorrow at 10:00 AM.', 'status' => 'unread', 'icon' => 'fas fa-calendar-check', 'url' => '/admin/bookings'],
            ['type' => 'payment', 'title' => 'Payment Received', 'message' => 'Received $32.00 from Priya Patel for Invoice #INV-1001.', 'status' => 'unread', 'icon' => 'fas fa-credit-card', 'url' => '/admin/payments'],
            ['type' => 'doubt', 'title' => 'New Doubt Submitted', 'message' => 'Aarav Kumar submitted a doubt in Science: "How plants make food".', 'status' => 'unread', 'icon' => 'fas fa-question-circle', 'url' => '/admin/doubts'],
            ['type' => 'refund', 'title' => 'Refund Requested', 'message' => 'Ishaan Verma requested a refund of $32.00 for a cancelled session.', 'status' => 'unread', 'icon' => 'fas fa-undo', 'url' => '/admin/refunds'],
            ['type' => 'registration', 'title' => 'New Student Registered', 'message' => 'Diya Gupta has just joined the platform.', 'status' => 'archived', 'icon' => 'fas fa-user-plus', 'url' => '/admin/students'],
            ['type' => 'payment', 'title' => 'Payment Failed', 'message' => 'Payment of $42.00 from Sneha Reddy failed due to card decline.', 'status' => 'read', 'icon' => 'fas fa-exclamation-triangle', 'url' => '/admin/payments'],
            ['type' => 'booking', 'title' => 'Session Cancelled', 'message' => 'Kabir Joshi cancelled his scheduled English session.', 'status' => 'read', 'icon' => 'fas fa-calendar-times', 'url' => '/admin/bookings'],
        ];

        foreach ($notifications as $data) {
            AdminNotification::create(array_merge($data, [
                'user_id' => $admin->id,
                'read_at' => $data['status'] === 'read' ? Carbon::now()->subDays(rand(1, 5))->toDateTimeString() : null,
            ]));
        }
    }

    private function seedAuditLogs(): void
    {
        $admin = User::where('role', 'admin')->first();
        $students = User::where('role', 'student')->get();

        $auditEntries = [
            ['user_id' => $admin?->id, 'role' => 'admin', 'module' => 'Settings', 'action' => 'updated', 'description' => 'Updated session price from $30.00 to $32.00.', 'ip_address' => '192.168.1.1'],
            ['user_id' => $admin?->id, 'role' => 'admin', 'module' => 'Students', 'action' => 'status_changed', 'description' => 'Deactivated student account: Ishaan Verma.', 'ip_address' => '192.168.1.1'],
            ['user_id' => $admin?->id, 'role' => 'admin', 'module' => 'Doubts', 'action' => 'status_updated', 'description' => 'Changed doubt status from pending to accepted for "Understanding circle theorems".', 'ip_address' => '192.168.1.1'],
            ['user_id' => $students->first()?->id, 'role' => 'student', 'module' => 'Bookings', 'action' => 'created', 'description' => 'Student booked a Mathematics session.', 'ip_address' => '10.0.0.5'],
            ['user_id' => $admin?->id, 'role' => 'admin', 'module' => 'Payments', 'action' => 'status_updated', 'description' => 'Marked payment txn_abc123 as successful.', 'ip_address' => '192.168.1.1'],
            ['user_id' => $admin?->id, 'role' => 'admin', 'module' => 'Refunds', 'action' => 'approved', 'description' => 'Approved refund request #1 for $32.00.', 'ip_address' => '192.168.1.1'],
            ['user_id' => $students->get(2)?->id, 'role' => 'student', 'module' => 'Doubts', 'action' => 'created', 'description' => 'Submitted a new doubt in Science: "Laws of motion".', 'ip_address' => '10.0.0.8'],
            ['user_id' => $admin?->id, 'role' => 'admin', 'module' => 'Subjects', 'action' => 'status_changed', 'description' => 'Disabled subject: Sanskrit.', 'ip_address' => '192.168.1.1'],
            ['user_id' => $admin?->id, 'role' => 'admin', 'module' => 'Bookings', 'action' => 'status_updated', 'description' => 'Cancelled appointment #5 due to student request.', 'ip_address' => '192.168.1.1'],
            ['user_id' => $students->get(5)?->id, 'role' => 'student', 'module' => 'Payments', 'action' => 'failed', 'description' => 'Payment of $32.00 failed: card declined.', 'ip_address' => '10.0.0.12'],
            ['user_id' => $admin?->id, 'role' => 'admin', 'module' => 'Students', 'action' => 'deleted', 'description' => 'Permanently deleted student account: Arjun Mehta.', 'ip_address' => '192.168.1.1'],
            ['user_id' => $admin?->id, 'role' => 'admin', 'module' => 'Settings', 'action' => 'updated', 'description' => 'Updated SMTP configuration settings.', 'ip_address' => '192.168.1.1'],
        ];

        foreach ($auditEntries as $index => $entry) {
            AuditLog::create(array_merge($entry, [
                'old_values' => null,
                'new_values' => null,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'created_at' => Carbon::now()->subDays(rand(0, 14))->subHours(rand(0, 23)),
            ]));
        }
    }
}
