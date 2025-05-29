<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Tingkat_prestasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tingkat_prestasi')->insert([
            [
                'nama_tingkat_prestasi' => 'Kampus',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tingkat_prestasi' => 'Kota',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tingkat_prestasi' => 'Provinsi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tingkat_prestasi' => 'Nasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_tingkat_prestasi' => 'Internasional',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
