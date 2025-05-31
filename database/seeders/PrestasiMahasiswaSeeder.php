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
                'id_tingkat_prestasi' => 1,
                'id_dospem' => $i,
                'nama_prestasi' => 'Lomba Kejuaraan ' . $i,
                'id_kategori' => $i,
                'juara' => 'Juara ' . $i,
                'tanggal_prestasi' => now()->subDays($i * 10),
                'id_periode' => 1,
                'deskripsi' => 'Prestasi yang bagus! pertahankan!' . $i,
                'foto_kegiatan' => 'foto' . $i . '.jpg',
                'bukti_sertifikat' => 'sertifikat' . $i . '.pdf',
                'surat_tugas' => 'surat' . $i . '.pdf',
                'karya' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
