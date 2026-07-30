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
        Schema::table('refunds', function (Blueprint $table) {
            if (!Schema::hasColumn('refunds', 'student_id')) {
                $table->foreignId('student_id')->nullable()->constrained('users')->nullOnDelete();
            }
            if (!Schema::hasColumn('refunds', 'invoice_id')) {
                $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            }
            if (!Schema::hasColumn('refunds', 'appointment_id')) {
                $table->foreignId('appointment_id')->nullable()->constrained('appointments')->nullOnDelete();
            }
            if (!Schema::hasColumn('refunds', 'refund_amount')) {
                $table->decimal('refund_amount', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('refunds', 'refund_date')) {
                $table->date('refund_date')->nullable();
            }
            if (!Schema::hasColumn('refunds', 'admin_notes')) {
                $table->text('admin_notes')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropColumn([
                'student_id',
                'invoice_id',
                'appointment_id',
                'refund_amount',
                'refund_date',
                'admin_notes',
            ]);
        });
    }
};
