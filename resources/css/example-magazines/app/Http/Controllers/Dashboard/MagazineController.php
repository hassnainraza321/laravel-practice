<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;
use DataTables;

class MagazineController extends Controller
{
    public function list(Request $request)
    {
        if (Auth::user()->is_admin === 1) {
            if (request()->method() === 'GET') {
            
                $data = DB::table('magazines');

                $magazines = $data->get();

                if ($request->ajax())
                {

                    if (!empty($request->keyword))
                    {
                        $keyword = trim($request->keyword);
                        
                        $data = $data->where(function($qry) use ($keyword) {
                                        $qry->orWhereRaw("name like ?", ["%{$keyword}%"])
                                        ->orWhereRaw("slug like ?", ["%{$keyword}%"]);
                                });
                    }
                   
                    $data = $data->select('*');

                    return DataTables::of($data)
                        ->addColumn('action', function ($row) {
                            $edit_url = route('magazines.edit', $row->id);
                            $delete_url = route('magazines.remove', $row->id);

                            return '<div class="btn-group">
                                        <button type="button" class="btn border-0 p-1 m-0 bg-danger text-white rounded" data-bs-toggle="dropdown" data-bs-target="#dropdown">. . .</button>
                                        <div id="dropdown" class="dropdown-menu">
                                            <button data-url="' . $edit_url . '" class="dropdown-item edit_magazine" type="button"><i data-feather="edit"></i> Edit</button>
                                            <a class="dropdown-item show_alert" data-url="' . $delete_url . '"><i data-feather="trash-2"></i> Delete</a>
                                        </div>
                                    </div>';
                        })
                        ->rawColumns(['action'])
                        ->make(true);

                }

                return view('dashboard.magazines.list', compact('magazines'));
            }

            $Validator = Validator::make($request->all(), [

                'name' => 'required',

            ]);

            if ($Validator->fails()) {
                
                return response()->json(['status' => 0, 'Validator' => $Validator->errors()]);
            }

            if (!is_null($request->slug)) {
                $slug = Str::slug($request->slug);
            }else{
                $name = substr($request->name, 0, 40);
                $slug = Str::slug($name);
            }

            $magazine_id = DB::table('magazines')->insertGetId([

                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description

            ]);

            if ($magazine_id) {

                return response()->json(['status' => 1, 'success' => 'Data inserted successfully'], 200);
            }

            return response()->json(['status' => 0, 'error' => 'Please Try Again !'], 500);
        }else
        {
            return response()->json(['status' => 0, 'error' => 'Only Admin Can Have Access !'], 500);
        }

    }

    public function edit(Request $request, $id)
    {
        if (Auth::user()->is_admin === 1) {
            if (request()->method() === 'GET') {
                
                $magazine = DB::table('magazines')->where('id', $id)->first();
                return response()->json(['status' => 1, 'modal' => view('dashboard.magazines.modal', compact('magazine'))->render()]);

            }

            $Validator = Validator::make($request->all(), [

                'name' => 'required',

            ]);

            if ($Validator->fails()) {
                
                return response()->json(['status' => 0, 'Validator' => $Validator->errors()]);
            }

            if (!is_null($request->slug)) {
                $slug = Str::slug($request->slug);
            }else{
                $name = substr($request->name, 0, 40);
                $slug = Str::slug($name);
            }

            $magazine = DB::table('magazines')->where('id', $id)->update([

                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description

            ]);

            if ($magazine) {

                return response()->json(['status' => 1, 'success' => 'Data updated successfully']);
            }

            return response()->json(['status' => 0, 'error' => 'Please Try Again !']);
        }else
        {
            return response()->json(['status' => 0, 'error' => 'Only Admin Can Have Access !']);
        }
    }

    public function remove(Request $request, $id)
    {
        if (Auth::user()->is_admin === 1) {
            $del_magazine = DB::table('magazines')->where('id', $id)->delete();

            if ($del_magazine) {
                
                return redirect()->back()->with('success', 'Magazine deleted successfully');
            }

            return redirect()->back()->with('error', 'Please Try Again !');
          }else
        {
            
            return redirect()->back()->with('error', 'Only Admin Can Have Access !');
        }  
    }
}
