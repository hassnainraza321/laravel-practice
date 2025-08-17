<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\CampaignRetry;
use App\Models\CampaignContact;
use DB;
use \Carbon\Carbon;
use App\Helpers\Helper;


class RunRetryCampaign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run_retry_campaign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retry the campaigns that were previously executed by the user and schedule retries for future attempts.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $campaigns = Campaign::where('retry_campaign', 1)->get();

        if ($campaigns->isEmpty()) 
        {
            return;
        }

        foreach ($campaigns as $campaign) 
        {
            $retries = CampaignRetry::where('campaign_id', $campaign->id)->get();

            if ($retries->isEmpty()) 
            {
                continue;
            }

            foreach ($retries as $retry) 
            {
                if (!empty($retry->hour) && !empty($retry->minute)) 
                {
                    $retry_time = Carbon::parse($campaign->created_at)->addHours($retry->hour)->addMinutes($retry->minute);

                    $current_time = Carbon::now();

                    if ($retry_time->equalTo($current_time)) 
                    {
                        $campaign_contacts = CampaignContact::where('campaign_id', $campaign->id)->get();
                        $campaign_response = Helper::runBroadcastCampaignFromMeta($campaign, $campaign_contacts);

                        if (is_array($campaign_response)) 
                        {
                            continue;
                        }
                    }
                }
            }
        }
    }
}
