<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Pendaftaran_lombaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            DB::table('pendaftaran_lomba')->insert([
                'id_mahasiswa' => $i,
                'id_lomba' => 1,
                'tanggal_pendaftaran' => now()->subDays($i),
                'status_pendaftaran' => 'Diterima',
                'berkas_pendaftaran' => 'berkas' . $i . '.pdf',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
