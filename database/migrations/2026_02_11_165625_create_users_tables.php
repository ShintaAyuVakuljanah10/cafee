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
    // Pastikan pakai Schema::dropIfExists agar lebih aman jika dijalankan ulang
    Schema::dropIfExists('users');
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('username')->unique();
        $table->string('password');
        $table->foreignId('role_id'); // Pakai role_id agar sinkron dengan Controller
        $table->string('foto')->nullable();
        $table->timestamps();
    });

    Schema::dropIfExists('password_reset_tokens');
    Schema::create('password_reset_tokens', function (Blueprint $table) {
        $table->string('username')->primary();
        $table->string('token');
        $table->timestamp('created_at')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
