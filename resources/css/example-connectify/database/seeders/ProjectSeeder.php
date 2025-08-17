<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Helpers\Helper;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user_id = DB::table('projects')->insert( [
            [
                'user_id' => 1, 
                'business_name' => 'GoArtisans',
                'phone_number' => '+92 300 4441019',
                'code' => 'AQCFsNfUM6tLc09daEojJfEuJoTRUqZKlqnytWBC3pYppcrHxv2BUX9d6IX05mNIWwUdXyUDvpNcAVFYDBopCkXnBp1nJ_Gp2-twLzzkv9-HM67mq1JgoBRdFZnxQBC4FtnF8xpPMvHW0GX4PDeMbTDFCT_al5L67ct9UjscvV2ZMRdQsGqeVou5Y2Y6DJuGsPKha9f0ebgiw2ngP_9XwKWenhrDqF7zeryaMnwDb3mKiLH7ivEd7fvdrIF6WldqFiXAkoB2cYRx9_Y1u1lluXYvX22qEeC2oFEezbC8lMuucRNjgUv7Zum2exJvEOGGAJSrpCC-olZw3ccP0e1mh93Bm48SOv7OCWag5cFDeGvF0lTY3Jn6SvB3c7q9P88JIwY',
                'access_token' => 'EAAQwDqYH6RcBOZCN0CaBWGVIZAsnisAde3MHDslGASEsPRBlSCC10mCVvHYZCdvMpVkDQeU8uTMffi3qmb5mZCQ2yO2z5rYwTcX2RQb2jUGUtKsBcieiFhY8n1KyEl21TaZAVZAThXbxeP2p55rfMYg7krfC0snArw1kZCBaxTZADCX119O9J4AZCHYB8sdkMJESC9RFHjIPUHVdCpoltIZBuS8ZAVtmvrOTGwekzOFnklkf7wvxScsSpf5ExD5XtUH',
                'phone_number_id' => '409881732210340',
                'whatsapp_business_account_id' => '419576484571442',
                'account_review_status' => 'APPROVED',
                'status' => 1,
                'reference_id' => Helper::getUUID(),
                'created_by' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1, 
                'business_name' => 'Testing',
                'phone_number' => '+92 323 8163140',
                'code' => '',
                'access_token' => '',
                'phone_number_id' => '',
                'whatsapp_business_account_id' => '',
                'account_review_status' => 'APPROVED',
                'status' => 1,
                'reference_id' => Helper::getUUID(),
                'created_by' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2, 
                'business_name' => 'Testing',
                'phone_number' => '+92 323 8163140',
                'code' => '',
                'access_token' => '',
                'phone_number_id' => '',
                'whatsapp_business_account_id' => '',
                'account_review_status' => 'APPROVED',
                'status' => 1,
                'reference_id' => Helper::getUUID(),
                'created_by' => 1,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ] );
    }
}
