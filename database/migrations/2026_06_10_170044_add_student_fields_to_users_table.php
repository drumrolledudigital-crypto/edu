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
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('student')->after('email'); // admin, student
            $table->string('parent_name')->nullable()->after('name');
            $table->string('mobile_number')->nullable()->after('email');
            $table->string('student_class')->nullable()->after('mobile_number'); // 'class' is a reserved word in some contexts, using student_class
            $table->boolean('is_active')->default(true)->after('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'parent_name', 'mobile_number', 'student_class', 'is_active']);
        });
    }
};
