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
        Schema::table('subjects', function (Blueprint $blueprint) {
            $blueprint->integer('class_range_from')->default(1)->after('description');
            $blueprint->integer('class_range_to')->default(8)->after('class_range_from');
            $blueprint->integer('session_duration')->default(50)->after('class_range_to');
            $blueprint->integer('sort_order')->default(0)->after('session_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $blueprint) {
            $blueprint->dropColumn(['class_range_from', 'class_range_to', 'session_duration', 'sort_order']);
        });
    }
};
