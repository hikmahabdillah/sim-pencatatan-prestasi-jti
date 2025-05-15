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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 20)->unique()->index();
            $table->string('nama',200);
            $table->integer('angkatan');
            $table->string('email');
            $table->string('no_hp', 20);
            $table->string('alamat');
            $table->unsignedBigInteger('id_prodi')->index();
            $table->unsignedBigInteger('id_minatBakat')->index();
            $table->timestamps();

            //foreign key
            $table->foreign('id_prodi')->references('id_prodi')->on('prodi');
            $table->foreign('id_minatBakat')->references('id_minatBakat')->on('minat_bakat');
            // Foreign key ke tabel pengguna
            $table->foreign('nim')->references('id_pengguna')->on('pengguna');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
