<?php

namespace Database\Seeders;

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
                'tahun_ajaran' => '2021/2022',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Genap',
                'tahun_ajaran' => '2021/2022',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Ganjil',
                'tahun_ajaran' => '2022/2023',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Genap',
                'tahun_ajaran' => '2022/2023',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Ganjil',
                'tahun_ajaran' => '2023/2024',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Genap',
                'tahun_ajaran' => '2023/2024',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Ganjil',
                'tahun_ajaran' => '2024/2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'semester' => 'Genap',
                'tahun_ajaran' => '2024/2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
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
        ]);
    }
}
