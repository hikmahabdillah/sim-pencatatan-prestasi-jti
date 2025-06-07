<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnggotaPrestasiSeeder extends Seeder
{
    public function run(): void
    {
        $anggotaData = [
            // Prestasi 1 (Tim Programming)
            [
                'id_prestasi' => 1,
                'id_mahasiswa' => 1,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 1,
                'id_mahasiswa' => 2,
                'peran' => 'anggota',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 1,
                'id_mahasiswa' => 3,
                'peran' => 'anggota',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Prestasi 2 (Individu Matematika)
            [
                'id_prestasi' => 2,
                'id_mahasiswa' => 4,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Prestasi 3 (Tim Desain UI/UX)
            [
                'id_prestasi' => 3,
                'id_mahasiswa' => 5,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 3,
                'id_mahasiswa' => 6,
                'peran' => 'anggota',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('anggota_prestasi')->insert($anggotaData);
    }
}
