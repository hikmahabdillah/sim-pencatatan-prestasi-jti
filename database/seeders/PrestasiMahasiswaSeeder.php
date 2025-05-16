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
        DB::table('prestasi_mahasiswa')->insert([
            [
                'nim' => '1231231231', // pastikan nim ini ada di tabel mahasiswa
                'id_tingkat_prestasi' => 5, // pastikan id ini ada di tabel tingkat_prestasi
                'id_dospem' => 'dosen', // pastikan id_dospem ini ada di tabel dosen_pembimbing
                'nama_prestasi' => 'Juara 1 Lomba Lempar Lembing',
                'id_kategori' => 2, // pastikan id ini ada di tabel kategori
                'juara' => '1',
                'tanggal_prestasi' => '2025-10-15',
                'id_periode' => 2, // pastikan id ini ada di tabel periode
                'keterangan' => 'Berhasil meraih juara 1 dalam lomba lempar lembing tingkat Internasional.',
                'foto_kegiatan' => 'foto_lomba.jpg',
                'bukti_sertifikat' => 'sertifikat_lomba.pdf',
                'surat_tugas' => 'surat_tugas_lomba.pdf',
                'karya' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1231231231', // pastikan nim ini ada di tabel mahasiswa
                'id_tingkat_prestasi' => 1, // pastikan id ini ada di tabel tingkat_prestasi
                'id_dospem' => 'dosenfalse', // pastikan id_dospem ini ada di tabel dosen_pembimbing
                'nama_prestasi' => 'Juara 1 Lomba Tari Tradisional',
                'id_kategori' => 3, // pastikan id ini ada di tabel kategori
                'juara' => '1',
                'tanggal_prestasi' => '2025-01-25',
                'id_periode' => 1, // pastikan id ini ada di tabel periode
                'keterangan' => 'Berhasil meraih juara 1 dalam lomba tari tingkat Kabupaten.',
                'foto_kegiatan' => 'foto_lomba.jpg',
                'bukti_sertifikat' => 'sertifikat_lomba.pdf',
                'surat_tugas' => 'surat_tugas_lomba.pdf',
                'karya' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1231231232', // pastikan nim ini ada di tabel mahasiswa
                'id_tingkat_prestasi' => 3, // pastikan id ini ada di tabel tingkat_prestasi
                'id_dospem' => 'dosen', // pastikan id_dospem ini ada di tabel dosen_pembimbing
                'nama_prestasi' => 'Juara 1 Lomba Debat Nasional',
                'id_kategori' => 4, // pastikan id ini ada di tabel kategori
                'juara' => '1',
                'tanggal_prestasi' => '2025-04-07',
                'id_periode' => 1, // pastikan id ini ada di tabel periode
                'keterangan' => 'Berhasil meraih juara 1 dalam lomba debat nasional tingkat universitas se-Indonesia.',
                'foto_kegiatan' => 'foto_lomba.jpg',
                'bukti_sertifikat' => 'sertifikat_lomba.pdf',
                'surat_tugas' => 'surat_tugas_lomba.pdf',
                'karya' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
