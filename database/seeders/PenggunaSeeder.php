<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pengguna')->insert([
            [
                'id_pengguna' => 'admin',
                'password' => 'admin',
                'role_id' => 1, // Admin
                'status_aktif' => true,
                'foto' => 'admin01.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 'dosen',
                'password' => 'dosen',
                'role_id' => 2, // Dosen Pembimbing
                'status_aktif' => true,
                'foto' => 'dosen001.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => '1231231231',
                'password' => 'true',
                'role_id' => 3, // Mahasiswa
                'status_aktif' => true,
                'foto' => 'mahasiswa001.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => '1231231232',
                'password' => 'false',
                'role_id' => 3,
                'status_aktif' => false,
                'foto' => 'mahasiswa002.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => 'dosenfalse',
                'password' => 'dosenfalse',
                'role_id' => 2,
                'status_aktif' => false,
                'foto' => 'dosen002.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_pengguna' => '1231231233',
                'password' => 'true',
                'role_id' => 3,
                'status_aktif' => true,
                'foto' => 'mahasiswa003.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
