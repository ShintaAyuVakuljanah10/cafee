<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('makanans', function (Blueprint $table) {
            $table->unsignedBigInteger('id_category')->after('id_makanan');

            $table->foreign('id_category')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade');

            $table->dropColumn('kategori'); // hapus kolom lama
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('makanans', function (Blueprint $table) {
            //
        });
    }
};
