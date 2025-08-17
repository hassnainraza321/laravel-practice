<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Helpers\Helper;
use DB;

class SyncTemplatesFromMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_templates_from_meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get and save all template which exists on meta in database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projects = Project::whereNotNull('access_token')->whereNotNull('phone_number_id')->whereNotNull('whatsapp_business_account_id')->where('status', 1)->get();

        if ($projects->isEmpty()) 
        {
            return;
        }

        foreach ($projects as $project) 
        {
             try {
                
                Helper::syncTemplatesFromMeta($project);

            } catch (\Exception $e) {
                \Log::error($e);
                continue;
            }
        }
    }
}
