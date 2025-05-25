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
        Schema::create('laporan_prestasi', function (Blueprint $table) {
            $table->id('id_laporan');
            $table->unsignedBigInteger('id_mahasiswa')->index();
            $table->unsignedBigInteger('id_prestasi')->index();
            $table->unsignedBigInteger('id_prodi')->index();
            $table->unsignedBigInteger('id_tingkat_prestasi')->index();
            $table->unsignedBigInteger('id_kategori')->index();
            $table->timestamps();

            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa');
            $table->foreign('id_prestasi')->references('id_prestasi')->on('prestasi_mahasiswa');
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori');
            $table->foreign('id_tingkat_prestasi')->references('id_tingkat_prestasi')->on('tingkat_prestasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_prestasi');
    }
};
