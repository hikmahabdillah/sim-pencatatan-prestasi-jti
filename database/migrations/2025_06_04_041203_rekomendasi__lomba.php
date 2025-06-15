<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekomendasi_lomba', function (Blueprint $table) {
            $table->id('id_rekomendasi');

            // Mahasiswa dan lomba
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_lomba');

            // Kriteria asli
            $table->float('c1_kesesuaian_minat')->nullable();
            $table->float('c2_jumlah_prestasi_sesuai')->nullable();
            $table->float('c3_tingkat_lomba')->nullable();
            $table->float('c4_durasi_pendaftaran')->nullable();
            $table->float('c5_biaya_pendaftaran')->nullable();
            $table->float('c6_benefit_lomba')->nullable();

            // Hasil normalisasi
            $table->float('n_c1')->nullable();
            $table->float('n_c2')->nullable();
            $table->float('n_c3')->nullable();
            $table->float('n_c4')->nullable();
            $table->float('n_c5')->nullable();
            $table->float('n_c6')->nullable();

            // Skor akhir
            $table->float('skor_moora')->nullable();

            $table->unsignedBigInteger('id_dospem')->nullable()->index();

            // Pengusul (admin/dosen)
            $table->unsignedBigInteger('id_pengusul')->nullable()->index(); // dari pengguna
            $table->unsignedBigInteger('role_pengusul')->nullable()->index(); // 1=admin, 2=dosen

            $table->boolean('notifikasi_terkirim')->default(false);

            // Timestamp rekomendasi
            $table->dateTime('tanggal_rekomendasi')->useCurrent();

            // Foreign keys
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_lomba')->references('id_lomba')->on('lomba')->onDelete('cascade');
            $table->foreign('id_dospem')->references('id_dospem')->on('dosen_pembimbing');
            $table->foreign('id_pengusul')->references('id_pengguna')->on('pengguna')->onDelete('set null');
            $table->foreign('role_pengusul')->references('role_id')->on('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_lomba');
    }
};