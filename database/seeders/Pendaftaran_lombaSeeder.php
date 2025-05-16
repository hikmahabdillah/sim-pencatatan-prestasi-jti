<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Pendaftaran_lombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pendaftaran_lomba')->insert([
            [
                'nim' => '1231231231', // Pastikan nim ini ada di tabel mahasiswa
                'id_lomba' => 1, // Pastikan id_lomba ini ada di tabel lomba
                'tanggal_pendaftaran' => '2025-05-01',
                'status_pendaftaran' => 'disetujui',
                'berkas_pendaftaran' => 'berkas_lomba.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1231231231',
                'id_lomba' => 2,
                'tanggal_pendaftaran' => '2025-04-20',
                'status_pendaftaran' => 'disetujui',
                'berkas_pendaftaran' => 'berkas_lomba.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1231231232', // Pastikan nim ini ada di tabel mahasiswa
                'id_lomba' => 3,
                'tanggal_pendaftaran' => '2025-05-03',
                'status_pendaftaran' => 'disetujui',
                'berkas_pendaftaran' => 'berkas_lomba.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1231231233',
                'id_lomba' => 4,
                'tanggal_pendaftaran' => '2026-02-10',
                'status_pendaftaran' => 'menunggu',
                'berkas_pendaftaran' => 'berkas_lomba.pdf',
                'created_at' => now(),
                'updated_at' => now(),  
            ],
            [
                'nim' => '1231231233',
                'id_lomba' => 4,
                'tanggal_pendaftaran' => '2026-02-11',
                'status_pendaftaran' => 'menunggu',
                'berkas_pendaftaran' => 'berkas_lomba.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
