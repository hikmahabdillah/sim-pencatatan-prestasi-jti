<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mahasiswa')->insert([
            [
                'nim' => '1231231231', // harus sesuai dengan id_pengguna di tabel 'pengguna'
                'nama' => 'mahasiswa1',
                'angkatan' => 2022,
                'email' => 'mahasiswa1@example.com',
                'no_hp' => '6212341234',
                'alamat' => 'Jalanan',
                'id_prodi' => 1, // pastikan id_prodi = 1 sudah ada di tabel 'prodi'
                'id_minatBakat' => 8, // pastikan id_minatBakat = 1 sudah ada di tabel 'minat_bakat'
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1231231232',
                'nama' => 'mahasiswa2',
                'angkatan' => 2023,
                'email' => 'mahasiswa2@example.com',
                'no_hp' => '6212341235',
                'alamat' => 'Jalanan juga',
                'id_prodi' => 2,
                'id_minatBakat' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1231231233',
                'nama' => 'mahasiswa3',
                'angkatan' => 2021,
                'email' => 'mahasiswa3@example.com',
                'no_hp' => '6212341236',
                'alamat' => 'Jalanan ujung',
                'id_prodi' => 2,
                'id_minatBakat' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
