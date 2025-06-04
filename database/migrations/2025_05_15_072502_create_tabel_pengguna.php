<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id('id_pengguna');
            $table->string('username', 20)->unique();
            $table->string('password', 255);
            $table->unsignedBigInteger('role_id')->index();
            $table->boolean('status_aktif')->default(true);
            $table->string('foto')->nullable();
            $table->text('keterangan_nonaktif')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')->references('role_id')->on('role');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
