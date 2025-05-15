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
        Schema::create('pengguna', function (Blueprint $table) {
            $table->string('id_pengguna', 20)->primary()->unique();
            $table->string('password',20);
            $table->unsignedBigInteger('role_id')->index(); 
            $table->boolean('status_aktif')->default(true);
            $table->string('foto');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_id')->references('role_id')->on('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
