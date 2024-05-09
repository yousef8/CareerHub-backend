<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ApplicationSeeder;
use Database\Seeders\JobPostSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JobPostSeeder::class,
            ApplicationSeeder::class,
            IndustrySeeder::class,
            SkillSeeder::class,
            JobPostSkillSeeder::class,
        ]);
        
    }
}
