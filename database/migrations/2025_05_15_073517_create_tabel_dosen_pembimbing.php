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
        Schema::create('dosen_pembimbing', function (Blueprint $table) {
            $table->string('id_dospem', 20)->primary()->index();
            $table->string('nama', 200);
            $table->string('email');
            $table->unsignedBigInteger('id_prodi')->index();
            $table->unsignedBigInteger('bidang_keahlian')->index();
            $table->timestamps();

            // Foreign key ke tabel prodi
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');

            // Foreign key ke tabel pengguna
            $table->foreign('id_dospem')->references('id_pengguna')->on('pengguna');

            //fk ke minat bakat
            $table->foreign('bidang_keahlian')->references('id_kategori')->on('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_pembimbing');
    }
};
