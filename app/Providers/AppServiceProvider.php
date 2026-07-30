<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant "Super Admin" role all permissions
        // This works in the context of Gate::allows()
        Gate::before(function (User $user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });

        // Share admin notification data with all admin views
        view()->composer('layouts.admin', function ($view) {
            try {
                if (Auth::check()) {
                    $unreadNotifsCount = \App\Models\AdminNotification::where('status', 'unread')->count();
                    $recentNotifs = \App\Models\AdminNotification::latest()->take(5)->get();
                } else {
                    $unreadNotifsCount = 0;
                    $recentNotifs = collect();
                }
            } catch (\Throwable $e) {
                $unreadNotifsCount = 0;
                $recentNotifs = collect();
            }
            $view->with(compact('unreadNotifsCount', 'recentNotifs'));
        });
    }
}
