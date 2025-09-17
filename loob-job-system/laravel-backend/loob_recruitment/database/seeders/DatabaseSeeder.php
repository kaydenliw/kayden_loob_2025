<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed users, job listings, and applications
        $this->call([
            UserSeeder::class,
            JobListingSeeder::class,
            ApplicationSeeder::class,
        ]);
    }
}
