<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Template;
use App\Models\Project;
use App\Helpers\Helper;
use DB;

class UpdateTemplatesStatusFromMeta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_templates_status_from_meta';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all templates status which are sending for approvel on meta.';

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
            $meta_templates = Helper::fetchTemplatesFromMeta($project);
        
            if (empty($meta_templates)) 
            {
                continue;
            }

            foreach ($meta_templates as $meta_template) 
            {
                $template = Template::where('meta_template_id', $meta_template['id'])->first();
                
                if (empty($template)) 
                {
                    continue;
                } 
                
                $template->status = $meta_template['status'];

                $template->save();
            }
        }
    }
}
