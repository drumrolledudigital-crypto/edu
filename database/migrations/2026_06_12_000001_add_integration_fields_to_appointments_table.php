<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->string('meet_status')->default('pending')->after('meet_link');
            $table->timestamp('meet_generated_at')->nullable()->after('meet_status');
            $table->string('calendar_event_id')->nullable()->after('meet_generated_at');
            $table->string('calendar_sync_status')->default('pending')->after('calendar_event_id');
            $table->timestamp('calendar_last_synced_at')->nullable()->after('calendar_sync_status');
            $table->string('email_notification_status')->default('pending')->after('calendar_last_synced_at');
            $table->timestamp('email_notification_sent_at')->nullable()->after('email_notification_status');
            $table->timestamp('rescheduled_at')->nullable()->after('email_notification_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'meet_status',
                'meet_generated_at',
                'calendar_event_id',
                'calendar_sync_status',
                'calendar_last_synced_at',
                'email_notification_status',
                'email_notification_sent_at',
                'rescheduled_at',
            ]);
        });
    }
};
