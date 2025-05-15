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
        Schema::create('pendaftaran_lomba', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->string('nim',20)->index();
            $table->unsignedBigInteger('id_lomba')->index();
            $table->date('tanggal_pendaftaran');
            $table->string('status_pendaftaran');
            $table->string('berkas_pendaftaran');
            $table->timestamps();

            $table->foreign('nim')->references('nim')->on('mahasiswa');
            $table->foreign('id_lomba')->references('id_lomba')->on('lomba');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_lomba');
    }
};
