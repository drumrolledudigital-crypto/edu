<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update Slots
        Schema::table('slots', function (Blueprint $table) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE slots MODIFY COLUMN status ENUM('available', 'booked', 'inactive') DEFAULT 'available'");
            }
        });

        // Update Appointments
        Schema::table('appointments', function (Blueprint $table) {
            if (!Schema::hasColumn('appointments', 'doubt_id')) {
                $table->foreignId('doubt_id')->nullable()->after('subject_id')->constrained()->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('appointments', 'appointment_date')) {
                $table->date('appointment_date')->nullable()->after('slot_id');
            }
            
            if (!Schema::hasColumn('appointments', 'start_time')) {
                $table->time('start_time')->nullable()->after('appointment_date');
            }
            
            if (!Schema::hasColumn('appointments', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
            
            if (!Schema::hasColumn('appointments', 'duration')) {
                $table->integer('duration')->default(50)->after('end_time');
            }

            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE appointments MODIFY COLUMN status ENUM('pending', 'scheduled', 'confirmed', 'completed', 'cancelled', 'rescheduled') DEFAULT 'pending'");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn(['appointment_date', 'start_time', 'end_time', 'duration']);
            $table->dropConstrainedForeignId('doubt_id');
        });
    }
};
