<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AllPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_pages_return_ok_or_redirect()
    {
        $pages = [
            '/',
            '/about',
            '/contact',
            '/faq',
            '/subjects',
            '/student/login',
            '/student/register',
            '/student/forgot-password',
            '/admin/login',
        ];

        foreach ($pages as $page) {
            $response = $this->get($page);
            $this->assertContains($response->getStatusCode(), [200, 302], "Page {$page} returned unexpected status {$response->getStatusCode()}");
        }
    }

    public function test_home_page_loads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_about_page_loads()
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
    }

    public function test_contact_page_loads()
    {
        $response = $this->get('/contact');
        $response->assertStatus(200);
    }

    public function test_faq_page_loads()
    {
        $response = $this->get('/faq');
        $response->assertStatus(200);
    }

    public function test_subjects_page_loads()
    {
        $response = $this->get('/subjects');
        $response->assertStatus(200);
    }

    public function test_student_login_page_loads()
    {
        $response = $this->get('/student/login');
        $response->assertStatus(200);
    }

    public function test_student_register_page_loads()
    {
        $response = $this->get('/student/register');
        $response->assertStatus(200);
    }

    public function test_admin_login_page_loads()
    {
        $response = $this->get('/admin/login');
        $response->assertStatus(200);
    }

    public function test_student_forgot_password_page_loads()
    {
        $response = $this->get('/student/forgot-password');
        $response->assertStatus(200);
    }
}
