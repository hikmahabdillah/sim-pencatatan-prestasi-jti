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
        Schema::create('anggota_prestasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_prestasi')->index();
            $table->unsignedBigInteger('id_mahasiswa')->index();
            $table->enum('peran', ['ketua', 'anggota']);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_prestasi')->references('id_prestasi')->on('prestasi_mahasiswa')->onDelete('cascade');
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');

            // Optional: mencegah duplikasi mahasiswa dalam 1 prestasi
            $table->unique(['id_prestasi', 'id_mahasiswa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_prestasi');
    }
};
