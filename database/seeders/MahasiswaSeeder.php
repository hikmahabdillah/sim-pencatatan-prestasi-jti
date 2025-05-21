<?php

namespace Database\Seeders;

use App\Models\MahasiswaModel;
use App\Models\PenggunaModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswas = [
            [
                'nim' => '20210001',
                'nama' => 'Fajar Setiawan',
                'angkatan' => 2021,
                'email' => 'fajar@example.com',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 1',
                'id_prodi' => 1,
                'id_kategori' => 1,
            ],
            [
                'nim' => '20210002',
                'nama' => 'Gita Permata',
                'angkatan' => 2021,
                'email' => 'gita@example.com',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Sudirman No. 2',
                'id_prodi' => 2,
                'id_kategori' => 2,
            ],
            [
                'nim' => '20210003',
                'nama' => 'Hendra Kurniawan',
                'angkatan' => 2021,
                'email' => 'hendra@example.com',
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Thamrin No. 3',
                'id_prodi' => 1,
                'id_kategori' => 3,
            ],
            [
                'nim' => '20220001',
                'nama' => 'Indah Puspita',
                'angkatan' => 2022,
                'email' => 'indah@example.com',
                'no_hp' => '081234567893',
                'alamat' => 'Jl. Gatot Subroto No. 4',
                'id_prodi' => 1,
                'id_kategori' => 4,
            ],
            [
                'nim' => '20220002',
                'nama' => 'Joko Prasetyo',
                'angkatan' => 2022,
                'email' => 'joko@example.com',
                'no_hp' => '081234567894',
                'alamat' => 'Jl. Asia Afrika No. 5',
                'id_prodi' => 2,
                'id_kategori' => 5,
            ],
            [
                'nim' => '20220003',
                'nama' => 'Kartika Sari',
                'angkatan' => 2022,
                'email' => 'kartika@example.com',
                'no_hp' => '081234567895',
                'alamat' => 'Jl. Diponegoro No. 6',
                'id_prodi' => 2,
                'id_kategori' => 1,
            ],
            [
                'nim' => '20230001',
                'nama' => 'Luki Hermawan',
                'angkatan' => 2023,
                'email' => 'luki@example.com',
                'no_hp' => '081234567896',
                'alamat' => 'Jl. Pahlawan No. 7',
                'id_prodi' => 1,
                'id_kategori' => 2,
            ],
        ];

        foreach ($mahasiswas as $mhs) {
            $pengguna = PenggunaModel::where('username', $mhs['nim'])->first();

            MahasiswaModel::create([
                'id_pengguna' => $pengguna->id_pengguna,
                'nim' => $mhs['nim'],
                'nama' => $mhs['nama'],
                'angkatan' => $mhs['angkatan'],
                'email' => $mhs['email'],
                'no_hp' => $mhs['no_hp'],
                'alamat' => $mhs['alamat'],
                'id_prodi' => $mhs['id_prodi'],
                'id_kategori' => $mhs['id_kategori'],
            ]);
        }
    }
}
