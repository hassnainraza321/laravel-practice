<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\Contact;
use App\Models\Project;
use App\Helpers\Helper;
use App\Models\Tag;
use DB;

class ImportTags implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $index => $import_data) 
        {
            if (empty($import_data['tag_name']) || empty($import_data['category']) || isset($import_data['customer_journey']) && empty($import_data['first_message'])) 
            {
                continue;
            }

            $tag_category_id = null;

            if (isset($import_data['category']) && !empty($import_data['category'])) 
            {
                $tag_category_id =  DB::table('tag_categories')->where('project_id', Helper::getProjectId())->where('name', $import_data['category'])->value('id');

                if (empty($tag_category_id)) 
                {
                    $tag_category_id =  DB::table('tag_categories')->insertGetId([
                        
                                            'project_id' => Helper::getProjectId(),
                                            'name' => $import_data['category'],
                                            'reference_id' => Helper::getUUID('tag_categories', 'reference_id'),
                                            'created_at' => now(),
                                            'updated_at' => now(), 

                                        ]);
                }
            }   

            $data = Tag::where('project_id', Helper::getProjectId())->where('title', $import_data['tag_name'])->first();

            if (empty($data)) 
            {
                $data = new Tag();
                $data->project_id = Helper::getProjectId();
            }

            $data->title = $import_data['tag_name'];
            $data->customer_journey = !empty($import_data['customer_journey']) && $import_data['customer_journey'] == 'Yes' ? 1 : 0;
            $data->tag_category_id = $tag_category_id ?? null;

            $data->save();

            if (isset($import_data['first_message']) && !empty($import_data['first_message'])) 
            {
                DB::table('tag_messages')->where('tag_id', $data->id)->delete();

                $first_message = explode(',', $import_data['first_message']);

                foreach ($first_message as $key => $first_message_value) 
                {
                    DB::table('tag_messages')->insert([
                        
                        'project_id' => Helper::getProjectId(),
                        'first_message' => $first_message_value,
                        'tag_id' => $data->id,
                        'reference_id' => Helper::getUUID('tag_messages', 'reference_id'),
                        'created_at' => now(),
                        'updated_at' => now(), 

                    ]);
                }
            }
        }
    }
}
