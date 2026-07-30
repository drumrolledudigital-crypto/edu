<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->role !== 'student') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request, AuditLoggerService $logger)
    {
        if ($request->expectsJson()) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
                $user = Auth::user();

                if ($user->role === 'student') {
                    Auth::logout();
                    return response()->json(['success' => false, 'message' => 'Unauthorized access. Student accounts cannot access the admin portal.'], 403);
                }

                if (!$user->is_active) {
                    Auth::logout();
                    return response()->json(['success' => false, 'message' => 'Your account is deactivated.'], 403);
                }

            $request->session()->regenerate();
            Cache::forget('admin.dashboard.stats');
            $logger->log('Auth', 'Login', "Admin user '{$user->name}' logged in via admin portal.");

            return response()->json(['success' => true, 'message' => 'Login successful. Redirecting...', 'redirect' => route('admin.dashboard')]);
            }

            return response()->json(['success' => false, 'message' => 'The provided credentials do not match our records.'], 401);
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $user = Auth::user();

            if ($user->role === 'student') {
                Auth::logout();
                return back()->withErrors(['email' => 'Unauthorized access. Student accounts cannot access the admin portal.']);
            }

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is deactivated.']);
            }

            $request->session()->regenerate();
            Cache::forget('admin.dashboard.stats');
            $logger->log('Auth', 'Login', "Admin user '{$user->name}' logged in via admin portal.");

            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request, AuditLoggerService $logger)
    {
        if (Auth::check()) {
            $logger->log('Auth', 'Logout', "Admin user '" . Auth::user()->name . "' logged out.");
        }

        Cache::forget('admin.dashboard.stats');
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
    }
}
