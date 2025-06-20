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
                'nama_lomba' => 'UI / UX Fest 2025',
                'penyelenggara' => 'Panitia UI/UX Fest',
                'id_tingkat_prestasi' => 3, // Provinsi
                'deskripsi' => 'Kompetisi desain UI/UX tingkat Provinsi.',
                'link_pendaftaran' => 'https://uifest2025.com',
                'periode' => '1',
                'biaya_pendaftaran' => true,
                'berhadiah' => true,
                'tanggal_mulai' => now()->addDays(21),
                'tanggal_selesai' => now()->addDays(25),
                'deadline_pendaftaran' => now()->addDays(20),
                'foto' => 'fotolomba.jpg',
                'tanggal_mulai' => now()->addDays(21),
                'tanggal_selesai' => now()->addDays(25),
                'deadline_pendaftaran' => now()->addDays(20),
                'foto' => 'fotolomba.jpg',
                'status_verifikasi' => null,
                'catatan_penolakan' => null,
                'added_by' => 16,
                'role_pengusul' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lomba' => 2,
                'nama_lomba' => 'Hacknation Malang',
                'penyelenggara' => 'Hacknation Indonesia',
                'id_tingkat_prestasi' => 2, // Kota
                'deskripsi' => 'Kompetisi hacking dan keamanan siber tingkat kota.',
                'link_pendaftaran' => 'https://hacknation.id/malang',
                'periode' => '1',
                'biaya_pendaftaran' => true,
                'berhadiah' => true,
                'tanggal_mulai' => now()->addDays(28),
                'tanggal_selesai' => now()->addDays(31),
                'deadline_pendaftaran' => now()->addDays(27),
                'tanggal_mulai' => now()->addDays(28),
                'tanggal_selesai' => now()->addDays(31),
                'deadline_pendaftaran' => now()->addDays(27),
                'foto' => 'fotolomba.jpg',
                'status_verifikasi' => null,
                'catatan_penolakan' => null,
                'added_by' => 16,
                'role_pengusul' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lomba' => 3,
                'nama_lomba' => 'Innovillage Telkom',
                'penyelenggara' => 'Telkom Indonesia',
                'id_tingkat_prestasi' => 4, // Nasional
                'deskripsi' => 'Kompetisi inovasi sosial berbasis teknologi.',
                'link_pendaftaran' => 'https://innovillage.id',
                'periode' => '1',
                'biaya_pendaftaran' => true,
                'berhadiah' => true,
                'tanggal_mulai' => now()->addDays(6),
                'tanggal_selesai' => now()->addDays(10),
                'deadline_pendaftaran' => now()->addDays(5),
                'foto' => 'fotolomba.jpg',
                'status_verifikasi' => null,
                'catatan_penolakan' => null,
                'added_by' => 16,
                'role_pengusul' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lomba' => 4,
                'nama_lomba' => 'AI Innovation Challenge by BRIN',
                'penyelenggara' => 'BRIN',
                'id_tingkat_prestasi' => 5, // Internasional
                'deskripsi' => 'Kompetisi AI bertaraf internasional.',
                'link_pendaftaran' => 'https://ai-challenge.brin.go.id',
                'periode' => '1',
                'biaya_pendaftaran' => false,
                'berhadiah' => true,
                'tanggal_mulai' => now()->addDays(9),
                'tanggal_selesai' => now()->addDays(14),
                'deadline_pendaftaran' => now()->addDays(8),
                'foto' => 'fotolomba.jpg',
                'status_verifikasi' => null,
                'catatan_penolakan' => null,
                'added_by' => 16,
                'role_pengusul' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lomba' => 5,
                'nama_lomba' => 'UI / UX Competition',
                'penyelenggara' => 'Komunitas Desain Kota Malang',
                'id_tingkat_prestasi' => 2, // Kota
                'deskripsi' => 'Lomba UI/UX tingkat kota untuk mahasiswa dan umum.',
                'link_pendaftaran' => 'https://uixmalang.id/kompetisi',
                'periode' => '1',
                'biaya_pendaftaran' => true,
                'berhadiah' => false,
                'tanggal_mulai' => now()->addDays(16),
                'tanggal_selesai' => now()->addDays(20),
                'deadline_pendaftaran' => now()->addDays(15),
                'foto' => 'fotolomba.jpg',
                'status_verifikasi' => null,
                'catatan_penolakan' => null,
                'added_by' => 16,
                'role_pengusul' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_lomba' => 6,
                'nama_lomba' => 'Web Developer Fest',
                'penyelenggara' => 'Komunitas Coding Kampus',
                'id_tingkat_prestasi' => 1, // Kampus
                'deskripsi' => 'Lomba web development antar mahasiswa kampus.',
                'link_pendaftaran' => 'https://webdevfest.com',
                'periode' => '1',
                'biaya_pendaftaran' => false,
                'berhadiah' => true,
                'tanggal_mulai' => now()->addDays(36),
                'tanggal_selesai' => now()->addDays(40),
                'deadline_pendaftaran' => now()->addDays(35),
                'tanggal_mulai' => now()->addDays(36),
                'tanggal_selesai' => now()->addDays(40),
                'deadline_pendaftaran' => now()->addDays(35),
                'foto' => 'fotolomba.jpg',
                'status_verifikasi' => null,
                'catatan_penolakan' => null,
                'added_by' => 16,
                'role_pengusul' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
