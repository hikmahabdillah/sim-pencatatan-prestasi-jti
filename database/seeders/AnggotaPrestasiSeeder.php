<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AnggotaPrestasiSeeder extends Seeder
{
    public function run(): void
    {
        $anggotaData = [
            // Prestasi 1 (Tim Programming) - 3 anggota
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

            // Prestasi 2 (Individu Matematika) - 1 mahasiswa
            [
                'id_prestasi' => 2,
                'id_mahasiswa' => 4,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Prestasi 3 (Tim Desain UI/UX) - 2 anggota
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
            ],

            // Prestasi 4 (UI Challenge Nasional - Individu)
            [
                'id_prestasi' => 4,
                'id_mahasiswa' => 8,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Prestasi 5 (Hackathon WebDev - Tim) - 3 anggota
            [
                'id_prestasi' => 5,
                'id_mahasiswa' => 8,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 5,
                'id_mahasiswa' => 1,
                'peran' => 'anggota',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 5,
                'id_mahasiswa' => 2,
                'peran' => 'anggota',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Prestasi 6 (National Tech Fair - Tim) - 2 anggota
            [
                'id_prestasi' => 6,
                'id_mahasiswa' => 8,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 6,
                'id_mahasiswa' => 3,
                'peran' => 'anggota',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Prestasi 7 (Digital Innovation Week - Individu)
            [
                'id_prestasi' => 7,
                'id_mahasiswa' => 8,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Prestasi 8 (Poster Design Festival - Individu)
            [
                'id_prestasi' => 8,
                'id_mahasiswa' => 8,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Prestasi 9 (Marketing Case Study - Tim) - 3 anggota
            [
                'id_prestasi' => 9,
                'id_mahasiswa' => 8,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 9,
                'id_mahasiswa' => 4,
                'peran' => 'anggota',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 9,
                'id_mahasiswa' => 5,
                'peran' => 'anggota',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Prestasi 10 (Mobile App Development - Tim) - 2 anggota
            [
                'id_prestasi' => 10,
                'id_mahasiswa' => 8,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 10,
                'id_mahasiswa' => 6,
                'peran' => 'anggota',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Prestasi 11 (Seni Digital Exhibition - Individu)
            [
                'id_prestasi' => 11,
                'id_mahasiswa' => 8,
                'peran' => 'ketua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('anggota_prestasi')->insert($anggotaData);
    }
}
