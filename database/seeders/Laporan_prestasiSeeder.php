<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Laporan_prestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for ($i = 1; $i <= 5; $i++) {
        //     DB::table('laporan_prestasi')->insert([
        //         'id_mahasiswa' => $i,
        //         'nama_mahasiswa' => 'Mahasiswa ' . $i,
        //         'prodi' => 1,
        //         'nama_lomba' => 'Lomba Nasional ' . $i,
        //         'tingkat' => 1,
        //         'kategori' => 1,
        //         'hasil' => 'Juara ' . $i,
        //         'status_verifikasi' => 'Terverifikasi',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
    }
}
