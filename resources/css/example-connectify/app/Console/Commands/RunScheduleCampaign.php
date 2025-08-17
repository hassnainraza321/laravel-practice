<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\CampaignContact;
use DB;
use \Carbon\Carbon;
use \Carbon\CarbonTimeZone;
use App\Helpers\Helper;

class RunScheduleCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run_schedule_campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the campaigns which is schedule by the user for future.';

    /**
     * Execute the console command.
     */
    
    public function handle() 
    {
        $currentUTC = Carbon::now('UTC');
        $campaigns = Campaign::where('schedule_date_and_time', 1)->whereNotNull('schedule_date')->whereNotNull('schedule_time')->whereNotNull('campaign_timezone')->get();

        if ($campaigns->isEmpty()) 
        {
            return;
        }

        foreach ($campaigns as $campaign) 
        {
            $timezone_name = trim($campaign->campaign_timezone);

            try {
                
                $user_time = $currentUTC->copy()->setTimezone($timezone_name);

                if ($user_time->eq(Carbon::parse("{$campaign->schedule_date} {$campaign->schedule_time}", $timezone_name))) 
                {
                    $campaign_contacts = CampaignContact::where('campaign_id', $campaign->id)->get();
                    $campaign_response = Helper::runBroadcastCampaignFromMeta($campaign, $campaign_contacts);

                    if (is_array($campaign_response)) 
                    {
                        continue;
                    }
                }
            } catch (\Exception $e) {
                \Log::error("Invalid timezone: " . $campaign->campaign_timezone);
                continue;
            }
        }
    }
}
