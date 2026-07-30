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
            if (!Schema::hasColumn('appointments', 'google_meet_link')) {
                $table->string('google_meet_link')->nullable()->after('meet_link');
            }
            if (!Schema::hasColumn('appointments', 'google_meet_id')) {
                $table->string('google_meet_id')->nullable()->after('google_meet_link');
            }
            if (!Schema::hasColumn('appointments', 'meeting_status')) {
                $table->string('meeting_status')->default('pending')->after('google_meet_id');
            }
            if (!Schema::hasColumn('appointments', 'meeting_generated_at')) {
                $table->timestamp('meeting_generated_at')->nullable()->after('meeting_status');
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
                'google_meet_link',
                'google_meet_id',
                'meeting_status',
                'meeting_generated_at'
            ]);
        });
    }
};
