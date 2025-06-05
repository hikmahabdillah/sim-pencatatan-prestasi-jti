<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrestasiMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            DB::table('prestasi_mahasiswa')->insert([
                'id_mahasiswa' => $i,
                'id_tingkat_prestasi' => $i,
                'id_dospem' => $i,
                'nama_prestasi' => 'Lomba Kejuaraan ' . $i,
                'id_kategori' => $i,
                'juara' => 'Juara ' . $i,
                'tanggal_prestasi' => now()->subDays($i * 10),
                'id_periode' => $i,
                'deskripsi' => 'Prestasi yang bagus! pertahankan!' . $i,
                'foto_kegiatan' => 'foto' . $i . '.jpg',
                'bukti_sertifikat' => 'sertifikat' . $i . '.pdf',
                'surat_tugas' => 'surat' . $i . '.pdf',
                'karya' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Data manual untuk id_mahasiswa = 8
        $manualData = [
            [
                'nama_prestasi' => 'UI Challenge Nasional',
                'id_kategori' => 11,
                'id_tingkat_prestasi' => 3,
                'juara' => 'Juara 1',
                'tahun' => 2023,
            ],
            [
                'nama_prestasi' => 'Hackathon WebDev',
                'id_kategori' => 15,
                'id_tingkat_prestasi' => 2,
                'juara' => 'Harapan 1',
                'tahun' => 2023,
            ],
            [
                'nama_prestasi' => 'National Tech Fair',
                'id_kategori' => 11,
                'id_tingkat_prestasi' => 3,
                'juara' => 'Juara 1',
                'tahun' => 2024,
            ],
            [
                'nama_prestasi' => 'Digital Innovation Week',
                'id_kategori' => 16,
                'id_tingkat_prestasi' => 4,
                'juara' => 'Juara 2',
                'tahun' => 2024,
            ],
            [
                'nama_prestasi' => 'Poster Design Festival',
                'id_kategori' => 17,
                'id_tingkat_prestasi' => 1,
                'juara' => 'Juara 3',
                'tahun' => 2023,
            ],
            [
                'nama_prestasi' => 'Marketing Case Study',
                'id_kategori' => 18,
                'id_tingkat_prestasi' => 3,
                'juara' => 'Juara 2',
                'tahun' => 2024,
            ],
            [
                'nama_prestasi' => 'Mobile App Development',
                'id_kategori' => 19,
                'id_tingkat_prestasi' => 2,
                'juara' => 'Harapan 2',
                'tahun' => 2024,
            ],
            [
                'nama_prestasi' => 'Seni Digital Exhibition',
                'id_kategori' => 20,
                'id_tingkat_prestasi' => 5,
                'juara' => 'Harapan 3',
                'tahun' => 2023,
            ],
        ];

        foreach ($manualData as $i => $data) {
            DB::table('prestasi_mahasiswa')->insert([
                'id_mahasiswa' => 8,
                'id_tingkat_prestasi' => $data['id_tingkat_prestasi'],
                'id_dospem' => 1,
                'nama_prestasi' => $data['nama_prestasi'],
                'id_kategori' => $data['id_kategori'],
                'juara' => $data['juara'],
                'tanggal_prestasi' => now()->setDate($data['tahun'], rand(1, 12), rand(1, 28)),
                'id_periode' => $data['tahun'] == 2023 ? 1 : 2,
                'deskripsi' => 'Prestasi dalam lomba ' . $data['nama_prestasi'],
                'foto_kegiatan' => 'foto_manual_' . $i . '.jpg',
                'bukti_sertifikat' => 'sertifikat_manual_' . $i . '.pdf',
                'surat_tugas' => 'surat_manual_' . $i . '.pdf',
                'karya' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
