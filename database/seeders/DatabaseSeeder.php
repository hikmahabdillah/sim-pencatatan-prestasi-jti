<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(ProdiSeeder::class);
        $this->call(PeriodeSeeder::class);
        $this->call(Tingkat_prestasiSeeder::class);
        $this->call(KategoriSeeder::class);
        $this->call(PenggunaSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(Dosen_pembimbingSeeder::class);
        $this->call(MahasiswaSeeder::class);
        $this->call(MinatBakatPenggunaSeeder::class);
        $this->call(LombaSeeder::class);
        $this->call(PrestasiMahasiswaSeeder::class);
        $this->call(Pendaftaran_lombaSeeder::class);
        $this->call(AnggotaPrestasiSeeder::class);
        $this->call(Laporan_prestasiSeeder::class);
        $this->call(Kategori_lomba_pivotSeeder::class);
    }
}
