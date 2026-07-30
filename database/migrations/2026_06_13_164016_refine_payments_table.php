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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'stripe_payment_id')) {
                $table->string('stripe_payment_id')->nullable()->after('appointment_id');
            }

            if (Schema::hasColumn('payments', 'user_id') && !Schema::hasColumn('payments', 'student_id')) {
                $table->renameColumn('user_id', 'student_id');
            }
        });

        if (Schema::hasColumn('payments', 'status') && !Schema::hasColumn('payments', 'payment_status')) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement("ALTER TABLE payments CHANGE COLUMN status payment_status ENUM('pending', 'successful', 'failed', 'refunded') DEFAULT 'pending'");
            } else {
                Schema::table('payments', function (Blueprint $table) {
                    $table->renameColumn('status', 'payment_status');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'payment_status')) {
                $table->renameColumn('payment_status', 'status');
            }
            if (Schema::hasColumn('payments', 'student_id')) {
                $table->renameColumn('student_id', 'user_id');
            }
            $table->dropColumn('stripe_payment_id');
        });
    }
};
