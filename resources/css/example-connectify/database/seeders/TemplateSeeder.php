<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Helpers\Helper;
use DB;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'UTILITY',
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MARKETING',
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AUTHENTICATION',
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $types = [
            [
                'name' => 'TEXT',
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'IMAGE',
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VIDEO',
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DOCUMENT',
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LOCATION',
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CAROUSEL',
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'LIMIT TIME OFFER',
                'reference_id' => Helper::getUUID(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $key => $category)
        {
            $category_id = DB::table('template_categories')->insertGetId( $category );
        }

        foreach ($types as $key => $type)
        {
            $type_id = DB::table('template_types')->insertGetId( $type );
        }
    }
}
