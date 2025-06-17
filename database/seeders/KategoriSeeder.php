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
                'nama_kategori' => 'Sains & Teknologi', //1
                'deskripsi' => 'Lomba dan prestasi di bidang sains, teknologi, matematika, komputer, dan inovasi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Olahraga', //2
                'deskripsi' => 'Lomba dan kejuaraan di bidang olahraga individu maupun beregu.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Enterpreneurship & Inovasi', //3
                'deskripsi' => 'Lomba kewirausahaan, startup, proposal bisnis, dan inovasi kreatif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Literasi & Bahasa', //4
                'deskripsi' => 'Kompetisi di bidang bahasa, sastra, puisi, pidato, storytelling, dan penerjemahan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Religi & Keagamaan', //5
                'deskripsi' => 'Lomba seperti MTQ, pidato keagamaan, kaligrafi, hafalan, dan dakwah.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Teknologi Informasi & Robotik', //6
                'deskripsi' => 'Kompetisi pemrograman, keamanan siber, robotika, dan teknologi digital.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Lingkungan & Sosial', //7
                'deskripsi' => 'Kompetisi dan penghargaan yang berfokus pada lingkungan hidup, pengabdian masyarakat, dan advokasi sosial.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'UI/UX', //8
                'deskripsi' => 'Kategori UI/UX mencakup segala hal yang berkaitan dengan perancangan antarmuka pengguna dan pengalaman pengguna yang optimal.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Keamanan Siber', //9
                'deskripsi' => 'Kategori Keamanan Siber mencakup topik-topik yang berkaitan dengan perlindungan sistem dan data dari ancaman digital.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Jaringan Komputer', //10
                'deskripsi' => 'Kategori Jaringan Komputer mencakup topik-topik yang berkaitan dengan pemeliharaan jaringan, dan lain sebagainya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Inovasi Teknologi', //11
                'deskripsi' => 'Kategori Inovasi Teknologi mencakup berbagai perkembangan dan penemuan baru dalam bidang teknologi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Artificial Intelligence', //12
                'deskripsi' => 'Kategori AI (Artificial Intelligence) mencakup segala bentuk kecerdasan buatan dan penerapannya di berbagai bidang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Pemrograman Website', //13
                'deskripsi' => 'Kategori Pemrograman Web mencakup materi seputar pembuatan dan pengembangan situs web.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Product Design', //14
                'deskripsi' => 'Kategori Product Design membahas proses kreatif dan teknis dalam menciptakan produk yang fungsional dan menarik.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Desain Grafis', //15
                'deskripsi' => 'Kategori Desain Grafis mencakup proses kreatif dalam merancang komunikasi visual melalui elemen grafis.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Bisnis Digital', //16
                'deskripsi' => 'Kategori Bisnis Digital membahas strategi, model, dan pengembangan bisnis berbasis digital.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Product Digital', //17
                'deskripsi' => 'Kategori product digital membahas strategi, model, dan pengembangan produk berbasis digital.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Mobile Programming', //18
                'deskripsi' => 'Kategori Mobile Programming berfokus pada pengembangan aplikasi untuk perangkat seluler.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Seni Digital', //19
                'deskripsi' => 'Kategori Seni Digital meliputi karya seni yang dibuat atau diproses secara digital, termasuk ilustrasi, animasi, dan media interaktif.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Data Science', //20
                'deskripsi' => 'Kategori Data Science mencakup kompetisi analisis data, machine learning, visualisasi data, dan pemodelan statistik yang menggunakan data nyata untuk menghasilkan insight atau prediksi.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_kategori' => 'Fullstack Developer', //21
                'deskripsi' => 'Kategori Fullstack Developer meliputi pengembangan aplikasi web atau mobile yang mencakup aspek front-end dan back-end, termasuk integrasi API, manajemen database, serta implementasi UI/UX.',
                'created_at' => now(),
                 'updated_at' => now(),
            ],
        ]);
    }
}
