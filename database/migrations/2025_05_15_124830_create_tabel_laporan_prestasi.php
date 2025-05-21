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
            $table->string('nama_mahasiswa');
            $table->unsignedBigInteger('prodi')->index();
            $table->string('nama_lomba');
            $table->unsignedBigInteger('tingkat')->index();
            $table->unsignedBigInteger('kategori')->index();
            $table->string('hasil');
            $table->string('status_verifikasi');
            $table->timestamps();

            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa');
            $table->foreign('prodi')->references('id_prodi')->on('prodi');
            $table->foreign('kategori')->references('id_kategori')->on('kategori');
            $table->foreign('tingkat')->references('id_tingkat_prestasi')->on('tingkat_prestasi');
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
