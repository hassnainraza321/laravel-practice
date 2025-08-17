<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Helpers\Helper;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        $user_id = DB::table('users')->insert( [
            [
                'first_name' => 'Admin',
                'display_name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin'),
                'role' => 1,
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Test',
                'display_name' => 'Test',
                'email' => 'test@gmail.com',
                'password' => bcrypt('test'),
                'role' => 1,
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ] 
        ] );
    }
}
