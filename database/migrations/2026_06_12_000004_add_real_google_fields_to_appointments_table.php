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
            if (!Schema::hasColumn('appointments', 'meet_event_id')) {
                $table->string('meet_event_id')->nullable()->after('meet_link');
            }
            if (!Schema::hasColumn('appointments', 'meet_metadata')) {
                $table->json('meet_metadata')->nullable()->after('meet_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            if (Schema::hasColumn('appointments', 'meet_metadata')) {
                $table->dropColumn('meet_metadata');
            }
            if (Schema::hasColumn('appointments', 'meet_event_id')) {
                $table->dropColumn('meet_event_id');
            }
        });
    }
};
