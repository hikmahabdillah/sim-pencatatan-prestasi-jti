<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_pembimbing', function (Blueprint $table) {
            $table->id('id_dospem'); // Primary key auto increment dengan nama spesifik
            $table->string('nip', 20)->unique();
            $table->unsignedBigInteger('id_pengguna')->index();
            $table->string('nama', 200);
            $table->string('email');
            $table->unsignedBigInteger('id_prodi')->index();
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_pembimbing');
    }
};
