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

class ImportContacts implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $index => $import_data) 
        {
            if (empty($import_data['name']) || empty($import_data['whatsapp_number']) || isset($import_data['tag']) && empty($import_data['tag'])) 
            {
                continue;
            }

            $tag = null;

            if (isset($import_data['tag']) && !empty($import_data['tag'])) 
            {
                $tag =  Tag::where('project_id', Helper::getProjectId())->where('title', $import_data['tag'])->first();

                if (empty($tag)) 
                {
                    $tag = new Tag();
                    $tag->project_id = Helper::getProjectId();
                }

                $tag->title = $import_data['tag'];

                $tag->save();
            }   

            $data = Contact::where('project_id', Helper::getProjectId())->where('whatsapp_number', $import_data['whatsapp_number'])->first();

            if (empty($data)) 
            {
                $data = new Contact();
                $data->project_id = Helper::getProjectId();
                $data->created_by = auth()->user()->id;
                $data->reference_id = Helper::getUUID('contacts', 'reference_id');
            }

            $data->name = $import_data['name'];
            $data->whatsapp_number = $import_data['whatsapp_number'];
            $data->source = $import_data['source'] ?? null;
            $data->tag_id = $tag->id ?? null;

            $data->save();
        }
    }
}
