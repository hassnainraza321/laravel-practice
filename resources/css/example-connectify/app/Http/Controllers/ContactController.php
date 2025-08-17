<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ImportContacts;
use App\Exports\ExportContacts;
use App\Models\Project;
use App\Models\Contact;
use App\Models\Tag;
use App\Helpers\Helper;
use DB;
use Excel;
use DataTables;
use \Carbon\Carbon;
 
class ContactController extends Controller
{
    public function list(Request $request)
    {
        $data = DB::table('contacts')
                ->leftJoin('tags', 'tags.id', '=', 'contacts.tag_id')
                ->where('contacts.project_id', Helper::getProjectId())
                ->where('contacts.id', '!=', 0);
        
        if ($request->ajax())
        {
            $data = $data->select('contacts.*', 'tags.title');

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
                    ->editColumn('source', function ($row) {
                        return !empty($row->source) ? $row->source : '-';
                    })
                    ->editColumn('title', function ($row) {
                        return !empty($row->title) ? $row->title : '-';
                    })
                    ->addColumn('actions', function ($row) {

                        return '<div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-'. $row->id .'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-'. $row->id .'">
                                        <a class="dropdown-item fetch-dynamic-modal" data-url="'. route('contacts.get', $row->id) .'">Edit</a>
                                        <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="'. $row->id .'">Remove</a>
                                    </div>
                                </div>';
                        
                    })
                    ->rawColumns(['index_data', 'actions'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->whereRaw("contacts.name like ?", ["%{$keyword}%"])
                                ->orWhereRaw("contacts.whatsapp_number like ?", ["%{$keyword}%"])
                                ->orWhereRaw("contacts.source like ?", ["%{$keyword}%"]);
                    })
                    ->order(function ($query) {

                        if (!empty(request()->sort_by))
                        {
                            $sort_data = explode('-', request()->sort_by);

                            if (isset($sort_data[0]) && !empty($sort_data[0]) && isset($sort_data[1]) && !empty($sort_data[1]) && in_array(strtolower($sort_data[1]), ['asc', 'desc']) && in_array(strtolower($sort_data[0]), ['name', 'created_at']))
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

        return view('contact.list')->with(compact('is_found'));
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
            $data = Contact::where('id', $id)->first();
        }

        return response()->json(['status' => 1, 'modal' => view('contact.modals.add', ['data' => $data])->render()]);
    }

    public function add(Request $request, $id = null)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }

        $validations = [
            'name' => 'required',
            'whatsapp_number' => 'required|string|regex:/^\+?[0-9]{1,15}$/'
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $tag = null;

        if (!empty($request->contact_tag)) 
        {
            $tag =  Tag::where('project_id', Helper::getProjectId())->where('title', $request->contact_tag)->first();

            if (empty($tag)) 
            {
                $tag = new Tag();
                $tag->project_id = Helper::getProjectId();
            }

            $tag->title = $request->contact_tag;

            $tag->save();   
        }

        $data = null;

        if (!empty($id))
        {
            $data = Contact::where('id', $id)->first();
        }

        $data_found = true;

        if (empty($data))
        {
            $data = new Contact();
            $data->project_id = Helper::getProjectId();
            $data->reference_id = Helper::getUUID('contacts', 'reference_id'); 
            $data->created_by = auth()->user()->id;
            $data_found = false;
        }

        $data->name = $request->name;
        $data->whatsapp_number = $request->country_code . $request->whatsapp_number;
        $data->source = $request->source;
        $data->tag_id = $tag->id ?? null;

        $data->save();

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

        $data = Contact::where('project_id', Helper::getProjectId())->whereIn('id', $ids)->delete();

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

        Excel::import(new ImportContacts, request()->file('file'));

        return response()->json(['status' => 1, 'refresh' => 1, 'message' => 'All contacts has been imported']);
    }
    
    public function export(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }

        return Excel::download(new ExportContacts, 'contacts.xlsx');
    }
}
