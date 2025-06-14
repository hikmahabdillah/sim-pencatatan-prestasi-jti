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
        Schema::create('kategori_lomba_pivot', function (Blueprint $table) {
            $table->unsignedBigInteger('id_lomba');
            $table->unsignedBigInteger('id_kategori');
            $table->timestamps();

            $table->primary(['id_lomba', 'id_kategori']);

            $table->foreign('id_lomba')
                ->references('id_lomba')
                ->on('lomba')
                ->onDelete('cascade');

            $table->foreign('id_kategori')
                ->references('id_kategori')
                ->on('kategori')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_lomba_pivot');
    }
};
