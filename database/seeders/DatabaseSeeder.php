<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            // KaryawanSeeder::class,
            UserSeeder::class,
            // DraftPekerjaanSeeder::class,
            // NeracaSeeder::class,
            // ArusKasSeeder::class,
            // LabaRugiSeeder::class,
            // JurnalUmumSeeder::class,
            // PerubahanModalSeeder::class,
        ]);
    }
}
