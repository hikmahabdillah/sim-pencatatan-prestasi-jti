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
            [
                'nama_kategori' => 'UI/UX (User Interface and User Experience design)',
                'deskripsi' => 'Kategori UI/UX mencakup segala hal yang berkaitan dengan perancangan antarmuka pengguna dan pengalaman pengguna yang optimal.',
                'created_at' => '2025-06-04 11:16:15',
                'updated_at' => '2025-06-04 11:16:15',
            ],
            [
                'nama_kategori' => 'Keamanan Siber',
                'deskripsi' => 'Kategori Keamanan Siber mencakup topik-topik yang berkaitan dengan perlindungan sistem dan data dari ancaman digital.',
                'created_at' => '2025-06-04 11:17:05',
                'updated_at' => '2025-06-04 11:17:05',
            ],
            [
                'nama_kategori' => 'Inovasi Teknologi',
                'deskripsi' => 'Kategori Inovasi Teknologi mencakup berbagai perkembangan dan penemuan baru dalam bidang teknologi.',
                'created_at' => '2025-06-04 11:17:49',
                'updated_at' => '2025-06-04 11:17:49',
            ],
            [
                'nama_kategori' => 'Artificial Intelligence',
                'deskripsi' => 'Kategori AI (Artificial Intelligence) mencakup segala bentuk kecerdasan buatan dan penerapannya di berbagai bidang.',
                'created_at' => '2025-06-04 11:18:47',
                'updated_at' => '2025-06-04 11:18:47',
            ],
            [
                'nama_kategori' => 'Pemrograman Website',
                'deskripsi' => 'Kategori Pemrograman Web mencakup materi seputar pembuatan dan pengembangan situs web.',
                'created_at' => '2025-06-04 11:19:20',
                'updated_at' => '2025-06-04 11:19:20',
            ],
            [
                'nama_kategori' => 'Product Design',
                'deskripsi' => 'Kategori Product Design membahas proses kreatif dan teknis dalam menciptakan produk yang fungsional dan menarik.',
                'created_at' => '2025-06-04 11:20:20',
                'updated_at' => '2025-06-04 11:20:20',
            ],
            [
                'nama_kategori' => 'Desain Grafis',
                'deskripsi' => 'Kategori Desain Grafis mencakup proses kreatif dalam merancang komunikasi visual melalui elemen grafis.',
                'created_at' => '2025-06-04 11:21:07',
                'updated_at' => '2025-06-04 11:21:07',
            ],
            [
                'nama_kategori' => 'Bisnis Digital',
                'deskripsi' => 'Kategori Bisnis Digital membahas strategi, model, dan pengembangan bisnis berbasis digital.',
                'created_at' => '2025-06-04 11:21:27',
                'updated_at' => '2025-06-04 11:21:27',
            ],
            [
                'nama_kategori' => 'Mobile Programming',
                'deskripsi' => 'Kategori Mobile Programming berfokus pada pengembangan aplikasi untuk perangkat seluler.',
                'created_at' => '2025-06-04 11:22:02',
                'updated_at' => '2025-06-04 11:22:02',
            ],
            [
                'nama_kategori' => 'Seni Digital',
                'deskripsi' => 'Kategori Seni Digital meliputi karya seni yang dibuat atau diproses secara digital, termasuk ilustrasi, animasi, dan media interaktif.',
                'created_at' => '2025-06-04 11:22:30',
                'updated_at' => '2025-06-04 11:22:30',
            ],
        ]);
    }
}
