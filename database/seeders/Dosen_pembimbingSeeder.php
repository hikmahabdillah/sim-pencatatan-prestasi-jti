<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Dosen_pembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dosen_pembimbing')->insert([
    [
        'id_dospem' => 'dosen', // harus sudah ada di tabel 'pengguna'
        'nama' => 'dosen baik',
        'email' => 'dosen.baik@gmail.com',
        'id_prodi' => 1, // pastikan id_prodi ini ada di tabel 'prodi'
        'bidang_keahlian' => 1, // pastikan id_minatBakat ini ada di tabel 'minat_bakat'
        'created_at' => now(),
        'updated_at' => now(),
    ],
    [
        'id_dospem' => 'dosenfalse',
        'nama' => 'dosen jahat',
        'email' => 'dosen.jahat@gmail.com',
        'id_prodi' => 2,
        'bidang_keahlian' => 2,
        'created_at' => now(),
        'updated_at' => now(),
    ],
]);
    }
}
