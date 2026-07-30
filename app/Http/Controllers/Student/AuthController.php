<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\IntegrationAutomationService;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function registerPost(Request $request, IntegrationAutomationService $automation, AuditLoggerService $logger)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_number' => ['required', 'string', 'max:20', 'unique:users'],
            'student_class' => ['required', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'parent_name' => $request->parent_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'student_class' => $request->student_class,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'is_active' => true,
        ]);

        // Send welcome email
        try {
            $automation->sendWelcomeEmail($user);
            $automation->createInternalAdminNotification(
                'Student',
                'New Student Registration',
                "{$user->name} has joined the platform.",
                $user,
                $user,
                route('admin.students.show', $user->id),
                'user'
            );
        } catch (\Throwable) {
            // Silently fail if mail fails
        }

        Auth::login($user);
        $logger->log('Auth', 'Create', "Student '{$user->name}' registered.", null, $user->toArray());

        return redirect()->route('student.dashboard')->with('success', 'Registration successful! Welcome to ' . \App\Models\Setting::get('platform_name', 'Drumroll Edu') . '.');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function loginPost(Request $request, AuditLoggerService $logger)
    {
        $request->validate([
            'login_id' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginField = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile_number';

        $credentials = [
            $loginField => $request->login_id,
            'password' => $request->password,
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Block admin/staff from logging in via student portal
            if (Auth::user()->role !== 'student') {
                Auth::logout();
                return back()->withErrors([
                    'login_id' => 'Please use the admin portal to log in.',
                ]);
            }

            if (!Auth::user()->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'login_id' => 'Your account is inactive. Please contact support.',
                ]);
            }
            
            $logger->log('Auth', 'Login', "Student '" . Auth::user()->name . "' logged in.");
            return redirect()->intended(route('student.dashboard'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'login_id' => 'The provided credentials do not match our records.',
        ])->onlyInput('login_id');
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request, AuditLoggerService $logger)
    {
        if (Auth::check()) {
            $logger->log('Auth', 'Logout', "User '" . Auth::user()->name . "' logged out.");
        }
        
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
