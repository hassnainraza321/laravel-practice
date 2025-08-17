<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Tag;
use App\Helpers\Helper;
use DB;

class ExportTags implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tag::leftJoin('tag_categories', 'tag_categories.id', '=', 'tags.tag_category_id')
            ->where('tags.project_id', Helper::getProjectId())
            ->where('tags.id', '!=', 0)
            ->select('tags.*', 'tag_categories.name as category_name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tag Name',
            'Category',
            'Customer Journey',
            'First Message',
            'Created At',
        ];
    }

    /**
     * Map data before exporting.
     */
    public function map($data): array
    {
        $tag_message = DB::table('tag_messages')->where('tag_id', $data->id)->pluck('first_message')->toArray();
        $first_message = !empty($tag_message) ? implode(', ', $tag_message) : '-';

        return [
            $data->title,
            !empty($data->category_name) ? $data->category_name : '-',
            $data->customer_journey == 1 ? 'Yes' : 'No',
            $first_message,
            $data->created_at->format('d/m/Y'),
        ];
    }
}
