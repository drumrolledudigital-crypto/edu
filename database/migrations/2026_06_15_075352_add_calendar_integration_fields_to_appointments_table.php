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
            if (!Schema::hasColumn('appointments', 'google_calendar_event_id')) {
                $table->string('google_calendar_event_id')->nullable()->after('meeting_generated_at');
            }
            if (!Schema::hasColumn('appointments', 'calendar_status')) {
                $table->string('calendar_status')->default('pending')->after('google_calendar_event_id');
            }
            if (!Schema::hasColumn('appointments', 'calendar_created_at')) {
                $table->timestamp('calendar_created_at')->nullable()->after('calendar_status');
            }
            if (!Schema::hasColumn('appointments', 'calendar_updated_at')) {
                $table->timestamp('calendar_updated_at')->nullable()->after('calendar_created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'google_calendar_event_id',
                'calendar_status',
                'calendar_created_at',
                'calendar_updated_at'
            ]);
        });
    }
};
