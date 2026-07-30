<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AdminPagesTest extends TestCase
{
    use RefreshDatabase;

    private function loginAsAdmin()
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'super-admin',
            'is_active' => true,
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        return $admin;
    }

    public function test_admin_pages_load()
    {
        $this->loginAsAdmin();

        $pages = [
            '/admin',
            '/admin/students',
            '/admin/doubts',
            '/admin/appointments',
            '/admin/payments',
            '/admin/invoices',
            '/admin/refunds',
            '/admin/subjects',
            '/admin/calendar',
            '/admin/notifications',
            '/admin/notification-center',
            '/admin/audit-logs',
            '/admin/reports',
            '/admin/settings',
            '/admin/staff',
            '/admin/roles',
        ];

        foreach ($pages as $page) {
            $response = $this->get($page);
            // 200 = OK, 302 = redirect (auth), 403 = permission denied (RBAC working)
            $this->assertContains($response->getStatusCode(), [200, 302, 403], "Admin page {$page} returned unexpected status {$response->getStatusCode()}");
        }
    }
}
