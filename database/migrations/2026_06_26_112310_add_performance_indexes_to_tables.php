<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->index('status');
            $table->index('appointment_date');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('payment_status');
            $table->index('stripe_payment_id');
        });

        Schema::table('slots', function (Blueprint $table) {
            $table->index(['status', 'date']);
        });

        Schema::table('doubts', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('module');
            $table->index('action');
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('email_logs', function (Blueprint $table) {
            $table->index('type');
            $table->index('status');
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['appointment_date']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['stripe_payment_id']);
        });

        Schema::table('slots', function (Blueprint $table) {
            $table->dropIndex(['status', 'date']);
        });

        Schema::table('doubts', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['module']);
            $table->dropIndex(['action']);
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['status']);
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
            $table->dropIndex(['status']);
        });
    }
};
