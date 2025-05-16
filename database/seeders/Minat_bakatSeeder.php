<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Minat_bakatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('minat_bakat')->insert([
            [
                'nama_minatBakat' => 'Bussines Plan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Pengembangan Web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Pengembangan Perangkat Lunak',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Data Mining',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Desain UI/UX',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Keamanan Siber',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Pemrograman Web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Olahraga',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Seni Musik',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Seni Tari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Seni Lukis',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Public Speaking',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_minatBakat' => 'Bahasa Inggris',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
