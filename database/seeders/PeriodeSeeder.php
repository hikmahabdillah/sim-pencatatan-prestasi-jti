<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('periode')->insert([
            [
                'semester' => 'Ganjil',
                'tahun_ajaran' => '2025/2026',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Genap',
                'tahun_ajaran' => '2025/2026',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Ganjil',
                'tahun_ajaran' => '2026/2027',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Genap',
                'tahun_ajaran' => '2026/2027',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Ganjil',
                'tahun_ajaran' => '2027/2028',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Genap',
                'tahun_ajaran' => '2027/2028',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
