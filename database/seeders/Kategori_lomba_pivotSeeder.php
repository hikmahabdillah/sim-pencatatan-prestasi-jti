<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Kategori_lomba_pivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = now();

        DB::table('kategori_lomba_pivot')->insert([
            ['id_lomba' => 1, 'id_kategori' => 10, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 1, 'id_kategori' => 16, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 1, 'id_kategori' => 17, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 2, 'id_kategori' => 12, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 2, 'id_kategori' => 11, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 3, 'id_kategori' => 13, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 3, 'id_kategori' => 19, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 4, 'id_kategori' => 14, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 4, 'id_kategori' => 23, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 4, 'id_kategori' => 22, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 5, 'id_kategori' => 16, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 5, 'id_kategori' => 17, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 5, 'id_kategori' => 11, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 6, 'id_kategori' => 15, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 6, 'id_kategori' => 23, 'created_at' => $timestamp, 'updated_at' => $timestamp],
            ['id_lomba' => 6, 'id_kategori' => 10, 'created_at' => $timestamp, 'updated_at' => $timestamp],
        ]);
    }
}