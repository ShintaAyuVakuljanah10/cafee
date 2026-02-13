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
        Schema::table('aplikasi', function (Blueprint $table) {
            $table->string('jam_weekday')->nullable()->after('weekday');
            $table->string('jam_weekend')->nullable()->after('weekend');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('aplikasi', function (Blueprint $table) {
            $table->dropColumn(['jam_weekday', 'jam_weekend']);
        });
    }
};
