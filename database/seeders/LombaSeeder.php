<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lomba')->insert([
            [
                'id_lomba' => 1,
                'nama_lomba' => 'Lomba Debat Mahasiswa Nasional',
                'penyelenggara' => 'Kementerian Pendidikan',
                'id_kategori' => 4, // Pastikan ada kategori dengan id_kategori = 1
                'id_tingkat_prestasi' => 3, // Pastikan ada tingkat prestasi dengan id_tingkat_prestasi = 1
                'deskripsi' => 'Kompetisi debat tingkat nasional untuk mahasiswa aktif.',
                'link_pendaftaran' => 'https://lombadebat.com',
                'tanggal_mulai' => '2025-04-01',
                'tanggal_selesai' => '2025-04-07',
                'deadline_pendaftaran' => '2025-03-31',
                'status_verifikasi' => 'disetujui',
                'added_by' => 'dosen', // Pastikan ada pengguna dengan id_pengguna = '22410001'
                'role_pengusul' => 2, // Pastikan ada role dengan role_id = 3 (contohnya Mahasiswa)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lomba' => 2,
                'nama_lomba' => 'Olimpics Games',
                'penyelenggara' => 'International Olympic Committee',
                'id_kategori' => 2, // Pastikan ada kategori dengan id_kategori = 3
                'id_tingkat_prestasi' => 5, // Pastikan ada tingkat prestasi dengan id_tingkat_prestasi = 2
                'deskripsi' => 'Kompetisi Kejuaraan Internasional dibidang olahraga',
                'link_pendaftaran' => 'https://olympicgames.com',
                'tanggal_mulai' => '2025-08-15',
                'tanggal_selesai' => '2025-10-15',
                'deadline_pendaftaran' => '2025-04-21',
                'status_verifikasi' => 'disetujui',
                'added_by' => 'dosen', // Pastikan ada pengguna dengan id_pengguna = '22410002'
                'role_pengusul' => 2, // Pastikan ada role dengan role_id = 3 (contohnya Mahasiswa)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lomba' => 3,
                'nama_lomba' => 'Lomba Kejuaraan Tari Tradisional',
                'penyelenggara' => 'Pemerintah Kabupaten Malang',
                'id_kategori' => 3, // Pastikan ada kategori dengan id_kategori = 3
                'id_tingkat_prestasi' => 1, // Pastikan ada tingkat prestasi dengan id_tingkat_prestasi = 2
                'deskripsi' => 'Lomba tari tradisional tingkat kabupaten.',
                'link_pendaftaran' => 'https://kabmalangtari.com',
                'tanggal_mulai' => '2025-01-20',
                'tanggal_selesai' => '2025-01-25',
                'deadline_pendaftaran' => '2025-01-18',
                'status_verifikasi' => 'disetujui',
                'added_by' => 'dosenfalse', // Pastikan ada pengguna dengan id_pengguna = '22410002'
                'role_pengusul' => 2, // Pastikan ada role dengan role_id = 3 (contohnya Mahasiswa)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lomba' => 4,
                'nama_lomba' => 'Lomba UI/UX',
                'penyelenggara' => 'Politeknik Negeri Malang',
                'id_kategori' => 9, // Pastikan ada kategori dengan id_kategori = 3
                'id_tingkat_prestasi' => 2, // Pastikan ada tingkat prestasi dengan id_tingkat_prestasi = 2
                'deskripsi' => 'Kompetisi Kejuaraan Desain',
                'link_pendaftaran' => 'https://polinema.ac.id/perlombaan',
                'tanggal_mulai' => '2026-03-01',
                'tanggal_selesai' => '2026-03-03',
                'deadline_pendaftaran' => '2026-02-20',
                'status_verifikasi' => 'menunggu',
                'added_by' => 'dosen', // Pastikan ada pengguna dengan id_pengguna = '22410002'
                'role_pengusul' => 2, // Pastikan ada role dengan role_id = 3 (contohnya Mahasiswa)
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lomba' => 5,
                'nama_lomba' => 'Lomba Makan Mie Ayam',
                'penyelenggara' => 'Karang Taruna WetanKali',
                'id_kategori' => 10, // Pastikan ada kategori dengan id_kategori = 3
                'id_tingkat_prestasi' => 1, // Pastikan ada tingkat prestasi dengan id_tingkat_prestasi = 2
                'deskripsi' => 'Kompetisi makan makanan terbanyak kota ngalam',
                'link_pendaftaran' => '-',
                'tanggal_mulai' => '2026-01-02',
                'tanggal_selesai' => '2026-01-02',
                'deadline_pendaftaran' => '2026-01-01',
                'status_verifikasi' => 'ditolak',
                'added_by' => 'dosenfalse', // Pastikan ada pengguna dengan id_pengguna = '22410002'
                'role_pengusul' => 2, // Pastikan ada role dengan role_id = 3 (contohnya Mahasiswa)
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
