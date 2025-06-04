<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MinatBakatPenggunaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('minat_bakat_pengguna')->truncate();

        $faker = Faker::create();
        $minatBakat = [];

        // For pengguna IDs 4 to 15
        foreach (range(4, 15) as $penggunaId) {
            // Determine how many categories this user will have (1-4)
            $categoryCount = $faker->numberBetween(1, 4);

            // Get random unique category IDs
            $categoryIds = $faker->randomElements(range(1, 10), $categoryCount);

            foreach ($categoryIds as $categoryId) {
                $minatBakat[] = [
                    'id_pengguna' => $penggunaId,
                    'id_kategori' => $categoryId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('minat_bakat_pengguna')->insert($minatBakat);
    }
}
