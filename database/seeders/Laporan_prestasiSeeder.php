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
        DB::table('laporan_prestasi')->insert([
            [
                'nim' => '1231231231', // Pastikan nim ini ada di tabel mahasiswa
                'nama_mahasiswa' => 'mahasiswa1',
                'prodi' => 1, // Pastikan id_prodi 1 ada di tabel prodi
                'nama_lomba' => 'Olimpics Games',
                'tingkat' => 5, // Pastikan id_tingkat_prestasi 1 ada di tabel tingkat_prestasi
                'kategori' => 2, // Pastikan id_kategori 1 ada di tabel kategori
                'hasil' => 'Juara 1',
                'status_verifikasi' => 'Disetujui',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1231231231',
                'nama_mahasiswa' => 'mahasiswa1',
                'prodi' => 1,
                'nama_lomba' => 'Juara 1 Lomba Tari Tradisional',
                'tingkat' => 1,
                'kategori' => 3,
                'hasil' => 'Juara 1',
                'status_verifikasi' => 'Menunggu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1231231232', // Pastikan nim ini ada di tabel mahasiswa
                'nama_mahasiswa' => 'mahasiswa2',
                'prodi' => 2,
                'nama_lomba' => 'Lomba Debat Nasional',
                'tingkat' => 3,
                'kategori' => 4,
                'hasil' => 'Juara 1',
                'status_verifikasi' => 'Disetujui',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
