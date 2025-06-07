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

    // // Data manual untuk id_mahasiswa = 8
    // $manualData = [
    //     [
    //         'nama_prestasi' => 'UI Challenge Nasional',
    //         'id_kategori' => 11,
    //         'id_tingkat_prestasi' => 3,
    //         'juara' => 'Juara 1',
    //         'tahun' => 2023,
    //     ],
    //     [
    //         'nama_prestasi' => 'Hackathon WebDev',
    //         'id_kategori' => 15,
    //         'id_tingkat_prestasi' => 2,
    //         'juara' => 'Harapan 1',
    //         'tahun' => 2023,
    //     ],
    //     [
    //         'nama_prestasi' => 'National Tech Fair',
    //         'id_kategori' => 11,
    //         'id_tingkat_prestasi' => 3,
    //         'juara' => 'Juara 1',
    //         'tahun' => 2024,
    //     ],
    //     [
    //         'nama_prestasi' => 'Digital Innovation Week',
    //         'id_kategori' => 16,
    //         'id_tingkat_prestasi' => 4,
    //         'juara' => 'Juara 2',
    //         'tahun' => 2024,
    //     ],
    //     [
    //         'nama_prestasi' => 'Poster Design Festival',
    //         'id_kategori' => 17,
    //         'id_tingkat_prestasi' => 1,
    //         'juara' => 'Juara 3',
    //         'tahun' => 2023,
    //     ],
    //     [
    //         'nama_prestasi' => 'Marketing Case Study',
    //         'id_kategori' => 18,
    //         'id_tingkat_prestasi' => 3,
    //         'juara' => 'Juara 2',
    //         'tahun' => 2024,
    //     ],
    //     [
    //         'nama_prestasi' => 'Mobile App Development',
    //         'id_kategori' => 19,
    //         'id_tingkat_prestasi' => 2,
    //         'juara' => 'Harapan 2',
    //         'tahun' => 2024,
    //     ],
    //     [
    //         'nama_prestasi' => 'Seni Digital Exhibition',
    //         'id_kategori' => 20,
    //         'id_tingkat_prestasi' => 5,
    //         'juara' => 'Harapan 3',
    //         'tahun' => 2023,
    //     ],
    // ];

    // foreach ($manualData as $i => $data) {
    //     DB::table('prestasi_mahasiswa')->insert([
    //         'id_mahasiswa' => 8,
    //         'id_tingkat_prestasi' => $data['id_tingkat_prestasi'],
    //         'id_dospem' => 1,
    //         'nama_prestasi' => $data['nama_prestasi'],
    //         'id_kategori' => $data['id_kategori'],
    //         'juara' => $data['juara'],
    //         'tanggal_prestasi' => now()->setDate($data['tahun'], rand(1, 12), rand(1, 28)),
    //         'id_periode' => $data['tahun'] == 2023 ? 1 : 2,
    //         'deskripsi' => 'Prestasi dalam lomba ' . $data['nama_prestasi'],
    //         'foto_kegiatan' => 'foto_manual_' . $i . '.jpg',
    //         'bukti_sertifikat' => 'sertifikat_manual_' . $i . '.pdf',
    //         'surat_tugas' => 'surat_manual_' . $i . '.pdf',
    //         'karya' => null,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);
    // }
}
