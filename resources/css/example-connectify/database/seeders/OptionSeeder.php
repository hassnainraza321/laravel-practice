<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Helpers\Helper;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        Helper::createUpdateOption('meta_app_id', '1178739380054295');
        Helper::createUpdateOption('meta_app_secret', '8d678b6cf8f8f81391f01eac78a265ad');
        Helper::createUpdateOption('meta_config_id', '864800658552445');
        Helper::createUpdateOption('meta_webhook_token', 'd343d40d86f7a2394c50026ab6555723d6fcefb6a4503042bfcbcc5dc975');
        Helper::createUpdateOption('meta_api_version', 'v20.0');
    }
}
