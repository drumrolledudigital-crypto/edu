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
        // Migrate existing data: wrap single titles in JSON array
        $doubts = DB::table('doubts')->select('id', 'title')->get();
        foreach ($doubts as $doubt) {
            $decoded = json_decode($doubt->title, true);
            if (is_null($decoded) && !empty($doubt->title)) {
                // It's a plain string, wrap in array
                DB::table('doubts')
                    ->where('id', $doubt->id)
                    ->update(['title' => json_encode([$doubt->title])]);
            }
        }

        Schema::table('doubts', function (Blueprint $table) {
            $table->json('title')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert JSON back to string (take first element)
        $doubts = DB::table('doubts')->select('id', 'title')->get();
        foreach ($doubts as $doubt) {
            $decoded = json_decode($doubt->title, true);
            if (is_array($decoded)) {
                DB::table('doubts')
                    ->where('id', $doubt->id)
                    ->update(['title' => $decoded[0] ?? '']);
            }
        }

        Schema::table('doubts', function (Blueprint $table) {
            $table->string('title')->change();
        });
    }
};
