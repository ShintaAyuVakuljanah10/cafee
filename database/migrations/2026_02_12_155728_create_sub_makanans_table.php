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
        Schema::create('sub_makanans', function (Blueprint $table) {
            $table->id('id_sub_makanan');
            $table->unsignedBigInteger('id_makanan');
            $table->string('nama'); // Level 1, Level 2, Es, Panas
            $table->integer('tambahan_harga')->default(0);
            $table->timestamps();

            $table->foreign('id_makanan')
                ->references('id_makanan')
                ->on('makanans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_makanans');
    }
};
