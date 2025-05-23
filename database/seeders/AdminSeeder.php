<?php

namespace Database\Seeders;

use App\Models\AdminModel;
use App\Models\PenggunaModel;
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
        $admins = [
            [
                'username' => 'admin1',
                'nama_admin' => 'Admin Utama',
                'email' => 'admin1@example.com',
            ],
            [
                'username' => 'admin2',
                'nama_admin' => 'Admin Sekunder',
                'email' => 'admin2@example.com',
            ],
            [
                'username' => 'admin3',
                'nama_admin' => 'Admin IT',
                'email' => 'admin3@example.com',
            ],
        ];

        foreach ($admins as $admin) {
            $pengguna = PenggunaModel::where('username', $admin['username'])->first();

            AdminModel::create([
                'id_pengguna' => $pengguna->id_pengguna,
                'username' => $admin['username'],
                'nama_admin' => $admin['nama_admin'],
                'email' => $admin['email'],
            ]);
        }
    }
}
