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
        $this->call(AdminSeeder::class);
        $this->call(OptionSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(TemplateSeeder::class);
        $this->call(CountrySeeder::class);
    }
}
