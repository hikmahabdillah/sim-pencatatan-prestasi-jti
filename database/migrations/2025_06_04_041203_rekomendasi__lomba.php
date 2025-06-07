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
        Schema::create('Rekomendasi_Lomba', function (Blueprint $table) {
            $table->id('id_rekomendasi');
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_lomba');

            // Kriteria Asli
            $table->float('c1_kesesuaian_minat')->nullable(false);
            $table->float('c2_jumlah_prestasi_sesuai')->nullable(false);
            $table->float('c3_tingkat_lomba')->nullable(false);
            $table->float('c4_durasi_pendaftaran')->nullable(false);
            $table->float('c5_biaya_pendaftaran')->nullable(false);
            $table->float('c6_benefit_lomba')->nullable(false);

            // Hasil Normalisasi
            $table->float('n_c1')->nullable(false);
            $table->float('n_c2')->nullable(false);
            $table->float('n_c3')->nullable(false);
            $table->float('n_c4')->nullable(false);
            $table->float('n_c5')->nullable(false);
            $table->float('n_c6')->nullable(false);


            // Skor Akhir MOORA
            $table->float('skor_moora')->nullable(false);

            // Timestamp
            $table->dateTime('tanggal_rekomendasi')->useCurrent();

            // Foreign Key
            $table->foreign('id_mahasiswa')->references('id_mahasiswa')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_lomba')->references('id_lomba')->on('lomba')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Rekomendasi_Lomba');
    }
};
