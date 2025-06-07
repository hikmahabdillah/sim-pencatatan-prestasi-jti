<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PrestasiMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $prestasiData = [
            [
                'id_prestasi' => 1,
                'id_tingkat_prestasi' => 1,
                'id_dospem' => 1,
                'nama_prestasi' => 'Lomba Programming Nasional',
                'id_kategori' => 1,
                'juara' => 'Juara 1',
                'tanggal_prestasi' => '2023-05-15',
                'id_periode' => 1,
                'tipe_prestasi' => 'tim',
                'deskripsi' => 'Memecahkan masalah algoritma dengan waktu tercepat',
                'foto_kegiatan' => 'programming_contest.jpg',
                'bukti_sertifikat' => 'sertifikat_programming.pdf',
                'surat_tugas' => 'surat_programming.pdf',
                'karya' => 'prototype.fig',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 2,
                'id_tingkat_prestasi' => 2,
                'id_dospem' => 2,
                'nama_prestasi' => 'Olimpiade Matematika',
                'id_kategori' => 2,
                'juara' => 'Juara 2',
                'tanggal_prestasi' => '2023-06-20',
                'id_periode' => 2,
                'tipe_prestasi' => 'individu',
                'deskripsi' => 'Menyelesaikan soal matematika tingkat tinggi',
                'foto_kegiatan' => 'math_olympiad.jpg',
                'bukti_sertifikat' => 'sertifikat_math.pdf',
                'surat_tugas' => 'surat_math.pdf',
                'karya' => 'prototype.fig',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_prestasi' => 3,
                'id_tingkat_prestasi' => 3,
                'id_dospem' => 3,
                'nama_prestasi' => 'Lomba Desain UI/UX',
                'id_kategori' => 3,
                'juara' => 'Juara 3',
                'tanggal_prestasi' => '2023-07-10',
                'id_periode' => 3,
                'tipe_prestasi' => 'tim',
                'deskripsi' => 'Membuat desain aplikasi yang user friendly',
                'foto_kegiatan' => 'uiux_contest.jpg',
                'bukti_sertifikat' => 'sertifikat_uiux.pdf',
                'surat_tugas' => 'surat_uiux.pdf',
                'karya' => 'prototype.fig',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('prestasi_mahasiswa')->insert($prestasiData);
    }
}
