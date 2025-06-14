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
        Schema::create('lomba', function (Blueprint $table) {
            $table->id('id_lomba');
            $table->string('nama_lomba');
            $table->string('penyelenggara');
            $table->unsignedBigInteger('id_tingkat_prestasi')->index();
            $table->text('deskripsi');
            $table->string('link_pendaftaran')->nullable();
            $table->unsignedBigInteger('periode');
            $table->boolean('biaya_pendaftaran');
            $table->boolean('berhadiah');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->date('deadline_pendaftaran');
            $table->string('foto');
            $table->text('catatan_penolakan')->nullable();
            $table->boolean('status_verifikasi')->nullable();
            $table->unsignedBigInteger('added_by')->index();
            $table->unsignedBigInteger('role_pengusul')->index();
            $table->enum('tipe_lomba', ['individu', 'tim']);
            $table->timestamps();

            $table->foreign('id_tingkat_prestasi')->references('id_tingkat_prestasi')->on('tingkat_prestasi');
            $table->foreign('periode')->references('id_periode')->on('periode');
            $table->foreign('added_by')->references('id_pengguna')->on('pengguna');
            $table->foreign('role_pengusul')->references('role_id')->on('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lomba');
    }
};
