<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class StudentPagesTest extends TestCase
{
    use RefreshDatabase;

    private function loginAsStudent()
    {
        $student = User::factory()->create([
            'email' => 'student@test.com',
            'password' => Hash::make('password'),
            'role' => 'student',
            'is_active' => true,
        ]);

        $this->post('/student/login', [
            'email' => 'student@test.com',
            'password' => 'password',
        ]);

        return $student;
    }

    public function test_student_pages_load()
    {
        $this->loginAsStudent();

        $pages = [
            '/student/dashboard',
            '/student/doubts',
            '/student/my-bookings',
            '/student/upcoming-sessions',
            '/student/past-sessions',
            '/student/invoices',
            '/student/payments/history',
            '/student/refunds',
            '/student/profile',
            '/student/book-session',
            '/student/submit-doubt',
        ];

        foreach ($pages as $page) {
            $response = $this->get($page);
            // 200 = OK, 302 = redirect, 403 = permission denied
            $this->assertContains($response->getStatusCode(), [200, 302, 403], "Student page {$page} returned unexpected status {$response->getStatusCode()}");
        }
    }
}
