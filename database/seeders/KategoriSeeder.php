<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kategori')->insert([
            [
                'nama_kategori' => 'Sains & Teknologi',
                'deskripsi' => 'Lomba dan prestasi di bidang sains, teknologi, matematika, komputer, dan inovasi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Olahraga',
                'deskripsi' => 'Lomba dan kejuaraan di bidang olahraga individu maupun beregu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Seni & Budaya',
                'deskripsi' => 'Lomba dan pertunjukan di bidang seni rupa, tari, musik, teater, dan budaya lokal.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Karya Tulis & Debat',
                'deskripsi' => 'Kompetisi yang melibatkan penulisan ilmiah, opini, debat, dan pidato.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Enterpreneurship & Inovasi',
                'deskripsi' => 'Lomba kewirausahaan, startup, proposal bisnis, dan inovasi kreatif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Literasi & Bahasa',
                'deskripsi' => 'Kompetisi di bidang bahasa, sastra, puisi, pidato, storytelling, dan penerjemahan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Religi & Keagamaan',
                'deskripsi' => 'Lomba seperti MTQ, pidato keagamaan, kaligrafi, hafalan, dan dakwah.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Teknologi Informasi & Robotik',
                'deskripsi' => 'Kompetisi pemrograman, keamanan siber, robotika, dan teknologi digital.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Desain & Multimedia',
                'deskripsi' => 'Lomba desain grafis, fotografi, videografi, animasi, dan desain UI/UX.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Lingkungan & Sosial',
                'deskripsi' => 'Kompetisi dan penghargaan yang berfokus pada lingkungan hidup, pengabdian masyarakat, dan advokasi sosial.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
