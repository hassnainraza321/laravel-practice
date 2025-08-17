<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Contact;
use App\Helpers\Helper;

class ExportContacts implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Contact::leftJoin('tags', 'tags.id', '=', 'contacts.tag_id')
                ->where('contacts.project_id', Helper::getProjectId())
                ->where('contacts.id', '!=', 0)->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Whatsapp Number',
            'Tag',
            'Source',
            'Created At',
        ];
    }

    /**
     * Map data before exporting.
     */
    public function map($data): array
    {
        return [
            $data->name,
            $data->whatsapp_number,
            $data->title,
            $data->source,
            $data->created_at->format('d/m/Y'),
        ];
    }
}
