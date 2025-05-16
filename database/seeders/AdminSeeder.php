<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin')->insert([
            [
                'id_admin' => 'admin', // Harus cocok dengan id_pengguna di tabel pengguna
                'nama_admin' => 'Admin1',
                'email' => 'admin@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
