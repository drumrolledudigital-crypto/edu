<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doubts', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('subject_id');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('subject_id');
            $table->index('slot_id');
            $table->index('doubt_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('appointment_id');
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->index('payment_id');
            $table->index('invoice_id');
            $table->index('appointment_id');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->index('student_id');
            $table->index('appointment_id');
            $table->index('payment_id');
        });

        Schema::table('email_logs', function (Blueprint $table) {
            $table->index('appointment_id');
            $table->index('recipient');
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('user_id');
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->index('user_id');
            $table->index(['related_type', 'related_id']);
        });
    }

    public function down(): void
    {
        Schema::table('doubts', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['subject_id']);
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['subject_id']);
            $table->dropIndex(['slot_id']);
            $table->dropIndex(['doubt_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
            $table->dropIndex(['appointment_id']);
        });

        Schema::table('refunds', function (Blueprint $table) {
            $table->dropIndex(['payment_id']);
            $table->dropIndex(['invoice_id']);
            $table->dropIndex(['appointment_id']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropIndex(['student_id']);
            $table->dropIndex(['appointment_id']);
            $table->dropIndex(['payment_id']);
        });

        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropIndex(['appointment_id']);
            $table->dropIndex(['recipient']);
        });

        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
        });

        Schema::table('admin_notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['related_type', 'related_id']);
        });
    }
};
