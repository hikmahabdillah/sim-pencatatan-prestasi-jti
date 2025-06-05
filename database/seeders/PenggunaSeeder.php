<?php

namespace Database\Seeders;

use App\Models\PenggunaModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penggunas = [
            // Admins
            [
                'username' => 'admin1',
                'password' => Hash::make('admin1'),
                'role_id' => 1,
                'status_aktif' => true,
            ],
            [
                'username' => 'admin2',
                'password' => Hash::make('admin2'),
                'role_id' => 1,
                'status_aktif' => true,
            ],
            [
                'username' => 'admin3',
                'password' => Hash::make('admin3'),
                'role_id' => 1,
                'status_aktif' => true,
            ],

            // Dosen Pembimbing
            [
                'username' => '0012345678',
                'password' => Hash::make('0012345678'),
                'role_id' => 2,
                'status_aktif' => true,
            ],
            [
                'username' => '0012345679',
                'password' => Hash::make('0012345679'),
                'role_id' => 2,
                'status_aktif' => true,
            ],
            [
                'username' => '0012345680',
                'password' => Hash::make('0012345680'),
                'role_id' => 2,
                'status_aktif' => true,
            ],
            [
                'username' => '0012345681',
                'password' => Hash::make('0012345681'),
                'role_id' => 2,
                'status_aktif' => true,
            ],
            [
                'username' => '0012345682',
                'password' => Hash::make('0012345682'),
                'role_id' => 2,
                'status_aktif' => true,
            ],

            // Mahasiswa
            [
                'username' => '20210001',
                'password' => Hash::make('20210001'),
                'role_id' => 3,
                'status_aktif' => true,
            ],
            [
                'username' => '20210002',
                'password' => Hash::make('20210002'),
                'role_id' => 3,
                'status_aktif' => true,
            ],
            [
                'username' => '20210003',
                'password' => Hash::make('20210003'),
                'role_id' => 3,
                'status_aktif' => true,
            ],
            [
                'username' => '20220001',
                'password' => Hash::make('20220001'),
                'role_id' => 3,
                'status_aktif' => true,
            ],
            [
                'username' => '20220002',
                'password' => Hash::make('20220002'),
                'role_id' => 3,
                'status_aktif' => true,
            ],
            [
                'username' => '20220003',
                'password' => Hash::make('20220003'),
                'role_id' => 3,
                'status_aktif' => true,
            ],
            [
                'username' => '20230001',
                'password' => Hash::make('20230001'),
                'role_id' => 3,
                'status_aktif' => true,
            ],
            [
                'username' => '23417200100',
                'password' => Hash::make('23417200100'),
                'role_id' => 3,
                'status_aktif' => true,
            ],
        ];

        foreach ($penggunas as $pengguna) {
            PenggunaModel::create($pengguna);
        }
    }
}
