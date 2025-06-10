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
        Schema::create('prestasi_mahasiswa', function (Blueprint $table) {
            $table->id('id_prestasi');
            $table->unsignedBigInteger('id_tingkat_prestasi')->index();
            $table->unsignedBigInteger('id_dospem')->nullable()->index();
            $table->string('nama_prestasi');
            $table->unsignedBigInteger('id_kategori')->index();
            $table->string('juara');
            $table->date('tanggal_prestasi');
            $table->unsignedBigInteger('id_periode')->index();
            $table->text('deskripsi')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto_kegiatan')->nullable();
            $table->string('bukti_sertifikat');
            $table->string('surat_tugas')->nullable();
            $table->string('karya');
            $table->enum('tipe_prestasi', ['individu', 'tim'])->default('individu');
            $table->boolean('status_verifikasi_dospem')->nullable();
            $table->boolean('status_verifikasi')->nullable();
            $table->timestamps();

            $table->foreign('id_tingkat_prestasi')->references('id_tingkat_prestasi')->on('tingkat_prestasi');
            $table->foreign('id_dospem')->references('id_dospem')->on('dosen_pembimbing');
            $table->foreign('id_periode')->references('id_periode')->on('periode');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestasi_mahasiswa');
    }
};
