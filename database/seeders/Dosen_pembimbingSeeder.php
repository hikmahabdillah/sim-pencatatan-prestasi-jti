<?php

namespace Database\Seeders;

use App\Models\DosenPembimbingModel;
use App\Models\PenggunaModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Dosen_pembimbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dospems = [
            [
                'nip' => '0012345678',
                'nama' => 'Prof. Dr. Ahmad Sanusi, M.Kom.',
                'email' => 'ahmad@example.com',
                'id_prodi' => 1,
                'bidang_keahlian' => 1,
            ],
            [
                'nip' => '0012345679',
                'nama' => 'Dr. Budi Santoso, M.Sc.',
                'email' => 'budi@example.com',
                'id_prodi' => 2,
                'bidang_keahlian' => 2,
            ],
            [
                'nip' => '0012345680',
                'nama' => 'Dr. Citra Dewi, M.T.',
                'email' => 'citra@example.com',
                'id_prodi' => 1,
                'bidang_keahlian' => 3,
            ],
            [
                'nip' => '0012345681',
                'nama' => 'Dr. Dedi Pratama, M.Kom.',
                'email' => 'dedi@example.com',
                'id_prodi' => 2,
                'bidang_keahlian' => 4,
            ],
            [
                'nip' => '0012345682',
                'nama' => 'Dr. Eka Wulandari, M.Sc.',
                'email' => 'eka@example.com',
                'id_prodi' => 2,
                'bidang_keahlian' => 5,
            ],
        ];

        foreach ($dospems as $dospem) {
            $pengguna = PenggunaModel::where('username', $dospem['nip'])->first();

            DosenPembimbingModel::create([
                'id_pengguna' => $pengguna->id_pengguna,
                'nip' => $dospem['nip'],
                'nama' => $dospem['nama'],
                'email' => $dospem['email'],
                'id_prodi' => $dospem['id_prodi'],
                'bidang_keahlian' => $dospem['bidang_keahlian'],
            ]);
        }
    }
}
