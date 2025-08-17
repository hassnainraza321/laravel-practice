<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\Importtags;
use App\Exports\Exporttags;
use App\Models\Project;
use App\Models\Contact;
use App\Models\Tag;
use App\Helpers\Helper;
use DB;
use Excel;
use DataTables;
use \Carbon\Carbon;

class TagController extends Controller
{
    public function list(Request $request)
    {
        $data = DB::table('tags')
                    ->leftJoin('tag_categories', 'tag_categories.id', '=', 'tags.tag_category_id')
                    ->where('tags.project_id', Helper::getProjectId());
        
        if ($request->ajax())
        {
            $data = $data->select('tags.*', 'tag_categories.name as category_name');

            return DataTables::of($data)
                    ->addColumn('index_data', function ($row) {

                        return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-'. $row->id .'" value="'. $row->id .'">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-'. $row->id .'">&nbsp;</label>
                                </div>';
                        
                    })
                    ->editColumn('created_at', function ($row) {
                        return $row->created_at ? with(new Carbon($row->created_at))->format('d/m/Y') : '';
                    })
                    ->addColumn('first_message', function ($row) {
                        $first_message = DB::table('tag_messages')->where('tag_id', $row->id)->pluck('first_message')->toArray();

                        return !empty($first_message) ? implode(', ', $first_message) : '-';
                    })
                    ->addColumn('category_name', function ($row) {

                        return $row->category_name ? $row->category_name : '-';
                    })
                    ->addColumn('customer_journey', function ($row) {

                        return $row->customer_journey == 1 ? 'Yes' : 'No';
                    })
                    ->addColumn('actions', function ($row) {

                        return '<div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-'. $row->id .'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-'. $row->id .'">
                                        <a class="dropdown-item fetch-dynamic-modal" data-url="'. route('tags.get', $row->id) .'">Edit</a>
                                        <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="'. $row->id .'">Remove</a>
                                    </div>
                                </div>';
                        
                    })
                    ->rawColumns(['index_data', 'first_message', 'actions'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->whereRaw("tags.title like ?", ["%{$keyword}%"]);
                    })
                    ->order(function ($query) {

                        if (!empty(request()->sort_by))
                        {
                            $sort_data = explode('-', request()->sort_by);

                            if (isset($sort_data[0]) && !empty($sort_data[0]) && isset($sort_data[1]) && !empty($sort_data[1]) && in_array(strtolower($sort_data[1]), ['asc', 'desc']) && in_array(strtolower($sort_data[0]), ['title', 'created_at']))
                            {
                                $query->orderBy(strtolower($sort_data[0]), strtolower($sort_data[1]));
                            }
                            else
                            {
                                $query->orderBy('id', 'desc');
                            }
                        }
                        else
                        {
                            $query->orderBy('id', 'desc');
                        }
                    })
                    ->make(true);
        }

        $is_found = $data->first();

        return view('manage.tag.list')->with(compact('is_found'));
    }

    public function get(Request $request, $id = null)
    {
        if (!$request->ajax())
        {
            return redirect()->back();
        }

        if (empty(Helper::getProjectId())) 
        {
            return response()->json(['status' => 1, 'redirect' => redirect()->route('projects')]);
        }

        $data = null;

        if (!empty($id)) 
        {
            $data = Tag::where('id', $id)->first();
        }

        return response()->json(['status' => 1, 'modal' => view('manage.tag.modals.add', ['data' => $data])->render()]);
    }

    public function add(Request $request, $id = null)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }

        $validations = [
            'title' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $tag_category_id = null;

        if (!empty($request->tag_category)) 
        {
            $tag_category_id =  DB::table('tag_categories')->where('project_id', Helper::getProjectId())->where('name', $request->tag_category)->value('id');

            if (empty($tag_category_id)) 
            {
                $tag_category_id =  DB::table('tag_categories')->insertGetId([
                    
                                        'project_id' => Helper::getProjectId(),
                                        'name' => $request->tag_category,
                                        'reference_id' => Helper::getUUID('tag_categories', 'reference_id'),
                                        'created_at' => now(),
                                        'updated_at' => now(), 

                                    ]);
            }
        }

        $data = null;

        if (!empty($id))
        {
            $data = Tag::where('id', $id)->first();
        }

        $data_found = true;

        if (empty($data))
        {
            $data = new Tag();
            $data->project_id = Helper::getProjectId();
            $data_found = false;
        }

        $data->title = $request->title;
        $data->customer_journey = $request->customer_journey ?? 0;
        $data->tag_category_id = $tag_category_id;
        $data->first_message = $request->first_message;

        $data->save();

        if (isset($request->first_message_value) && !empty($request->first_message_value)) 
        {
            DB::table('tag_messages')->where('tag_id', $data->id)->delete();

            foreach ($request->first_message_value as $key => $first_message_value) 
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

        return response()->json(['status' => 1, 'refresh' => 1]);
    }

    public function remove(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }

        if (empty($request->id))
        {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        $ids = explode(',', $request->id);

        if (empty($ids))
        {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        $data = Tag::where('project_id', Helper::getProjectId())->whereIn('id', $ids)->delete();

        return response()->json(['status' => 1, 'message' => 'Done']);
    }

    public function import(Request $request)
    {
        if (!$request->ajax())
        {
            return redirect()->back();
        }

        if (empty(Helper::getProjectId())) 
        {
            return response()->json(['status' => 1, 'redirect' => redirect()->route('projects')]);
        }

        $validations = [
            'file' => 'required|mimes:csv,xlsx',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        Excel::import(new ImportTags, request()->file('file'));

        return response()->json(['status' => 1, 'refresh' => 1, 'message' => 'All tags has been imported']);
    }
    
    public function export(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }

        return Excel::download(new ExportTags, 'tags.xlsx');
    }
}
