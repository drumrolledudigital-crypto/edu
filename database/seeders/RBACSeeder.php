<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Permissions
        $permissions = [
            // Student Management
            ['name' => 'View Students', 'slug' => 'view-students', 'group' => 'Students'],
            ['name' => 'Manage Students', 'slug' => 'manage-students', 'group' => 'Students'],
            
            // Subject Management
            ['name' => 'View Subjects', 'slug' => 'view-subjects', 'group' => 'Subjects'],
            ['name' => 'Manage Subjects', 'slug' => 'manage-subjects', 'group' => 'Subjects'],

            // Book Management
            ['name' => 'View Books', 'slug' => 'view-books', 'group' => 'Books'],
            ['name' => 'Manage Books', 'slug' => 'manage-books', 'group' => 'Books'],
            ['name' => 'View Book Purchases', 'slug' => 'view-book-purchases', 'group' => 'Books'],
            
            // Doubt Management
            ['name' => 'View Doubts', 'slug' => 'view-doubts', 'group' => 'Doubts'],
            ['name' => 'Manage Doubts', 'slug' => 'manage-doubts', 'group' => 'Doubts'],
            
            // Booking Management
            ['name' => 'View Bookings', 'slug' => 'view-bookings', 'group' => 'Bookings'],
            ['name' => 'Manage Bookings', 'slug' => 'manage-bookings', 'group' => 'Bookings'],
            
            // Payment Management
            ['name' => 'View Payments', 'slug' => 'view-payments', 'group' => 'Payments'],
            ['name' => 'Manage Payments', 'slug' => 'manage-payments', 'group' => 'Payments'],
            
            // Invoice Management
            ['name' => 'View Invoices', 'slug' => 'view-invoices', 'group' => 'Invoices'],
            ['name' => 'Manage Invoices', 'slug' => 'manage-invoices', 'group' => 'Invoices'],
            
            // Refund Management
            ['name' => 'View Refunds', 'slug' => 'view-refunds', 'group' => 'Refunds'],
            ['name' => 'Manage Refunds', 'slug' => 'manage-refunds', 'group' => 'Refunds'],
            
            // Notification Management
            ['name' => 'View Notifications', 'slug' => 'view-notifications', 'group' => 'Notifications'],
            ['name' => 'Manage Notifications', 'slug' => 'manage-notifications', 'group' => 'Notifications'],
            
            // System
            ['name' => 'View Audit Logs', 'slug' => 'view-audit-logs', 'group' => 'System'],
            ['name' => 'View Reports', 'slug' => 'view-reports', 'group' => 'System'],
            ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'group' => 'System'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'group' => 'System'],
            ['name' => 'Manage Staff', 'slug' => 'manage-staff', 'group' => 'System'],
        ];

        foreach ($permissions as $p) {
            Permission::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // Define Roles
        $superAdmin = Role::updateOrCreate(['slug' => 'super-admin'], [
            'name' => 'Super Admin',
            'description' => 'Has absolute access to all system features.'
        ]);

        $admin = Role::updateOrCreate(['slug' => 'admin'], [
            'name' => 'Admin',
            'description' => 'Full administrative access except system settings.'
        ]);

        $manager = Role::updateOrCreate(['slug' => 'manager'], [
            'name' => 'Manager',
            'description' => 'Can manage students, bookings, and subjects.'
        ]);

        $support = Role::updateOrCreate(['slug' => 'support-staff'], [
            'name' => 'Support Staff',
            'description' => 'Can view most data and manage doubts/notifications.'
        ]);

        // Assign Permissions to Roles (Admin/Manager/Support)
        // Super Admin doesn't need explicit assignments due to bypass, but let's assign all for clarity if needed.
        $allPermissions = Permission::all();
        $superAdmin->permissions()->sync($allPermissions->pluck('id'));

        // Admin Permissions (Everything except critical system configs/roles/logs maybe?)
        $adminPermissions = $allPermissions->filter(function($p) {
            return !in_array($p->slug, ['manage-settings', 'manage-roles', 'view-audit-logs']);
        });
        $admin->permissions()->sync($adminPermissions->pluck('id'));

        // Manager Permissions
        $managerPermissions = $allPermissions->filter(function($p) {
            return in_array($p->group, ['Students', 'Subjects', 'Books', 'Bookings', 'Doubts']);
        });
        $manager->permissions()->sync($managerPermissions->pluck('id'));

        // Support Permissions
        $supportPermissions = $allPermissions->filter(function($p) {
            return in_array($p->slug, ['view-students', 'view-subjects', 'view-bookings', 'view-payments', 'view-invoices', 'manage-doubts', 'manage-notifications']);
        });
        $support->permissions()->sync($supportPermissions->pluck('id'));

        // Assign default Super Admin role to the existing main admin
        $mainAdmin = User::where('email', 'admin@drumroll.com')->first();
        if ($mainAdmin) {
            $mainAdmin->roles()->sync([$superAdmin->id]);
        }

        // Create requested Super Admin user
        $superUser = User::updateOrCreate(['email' => 'super@gmail.com'], [
            'name' => 'System Super Admin',
            'password' => \Illuminate\Support\Facades\Hash::make('2580'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        $superUser->roles()->sync([$superAdmin->id]);
    }
}
