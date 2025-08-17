<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;
use DataTables;

class PackageController extends Controller
{
    public function add(Request $request)
    {
        if (Auth::user()->is_admin === 1) {
            if (request()->method() === 'GET') {

                $data = DB::table('packages');

                $packages = $data->get();

                if ($request->ajax())
                {

                    if (!empty($request->keyword))
                    {
                        $keyword = trim($request->keyword);
                        
                        $data = $data->where(function($qry) use ($keyword) {
                                        $qry->orWhereRaw("name like ?", ["%{$keyword}%"])
                                        ->orWhereRaw("description like ?", ["%{$keyword}%"]);
                                });
                    }
                   
                    $data = $data->select('*');

                    return DataTables::of($data)
                        ->addColumn('amount', function($row){

                            $amount = $row->amount == 0 ? 'Free' : '$'.$row->amount;
                            return $amount;
                        })
                        ->addColumn('article_limit', function($row){
                            $article_limit = $row->article_limit === 0 ? 'Unlimited' : $row->article_limit;
                            return $article_limit;
                        })
                        ->addColumn('action', function ($row) {
                            $edit_url = route('packages.edit', $row->id);
                            $delete_url = route('packages.remove', $row->id);

                            return '<div class="btn-group">
                                        <button type="button" class="btn border-0 p-1 m-0 bg-danger text-white rounded" data-bs-toggle="dropdown" data-bs-target="#dropdown">. . .</button>
                                        <div id="dropdown" class="dropdown-menu">
                                            <button data-url="' . $edit_url . '" class="dropdown-item edit_magazine" type="button"><i data-feather="edit"></i> Edit</button>
                                            <a class="dropdown-item show_alert" data-url="' . $delete_url . '"><i data-feather="trash-2"></i> Delete</a>
                                        </div>
                                    </div>';
                        })
                        ->rawColumns(['amount', 'article_limit', 'action'])
                        ->make(true);

                }

                return view('dashboard.packages.add', compact('packages'));
            }

            
            $Validator = Validator::make($request->all(), [

                'name' => 'required',
                'description' => 'required',
                'amount' => 'required',
                'article_limit' => 'required',

            ]);

            if ($Validator->fails()) {
                
                return response()->json(['status' => 0, 'Validator' => $Validator->errors()]);
            }

            $package_id = DB::table('packages')->insertGetId([

                'name' => $request->name,
                'description' => $request->description,
                'amount' => $request->amount,
                'article_limit' => $request->article_limit,
                'created_at' => now(),
                'updated_at' => now()

            ]);

            if ($package_id) {

                return response()->json(['status' => 1, 'success' => 'Data inserted successfully']);
            }

            return response()->json(['status' => 0, 'error' => 'Please Try Again !']);
        }else
        {
            return response()->json(['status' => 0, 'error' => 'Only Admin Can Have Access !']);
        }
    }

    public function edit(Request $request, $id)
    {
        if (Auth::user()->is_admin === 1) {
            if (request()->method() === 'GET') {

                $package = DB::table('packages')->where('id', $id)->first();

                return response()->json(['status' => 1, 'modal' => view('dashboard.packages.modal', compact('package', 'id'))->render()]);

            }

            
            $Validator = Validator::make($request->all(), [

                'name' => 'required',
                'description' => 'required',
                'amount' => 'required',
                'article_limit' => 'required',

            ]);

            if ($Validator->fails()) {
                
                return response()->json(['status' => 0, 'Validator' => $Validator->errors()]);
            }

            $package_update = DB::table('packages')->where('id', $id)->update([

                'name' => $request->name,
                'description' => $request->description,
                'amount' => $request->amount,
                'article_limit' => $request->article_limit,
                'updated_at' => now()

            ]);

            if ($package_update) {

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
            $del_package = DB::table('packages')->where('id', $id)->delete();


            if ($del_package) {
                
                return redirect()->back()->with('success', 'Package deleted successfully');
            }

            return redirect()->back()->with('error', 'Please Try Again !');
        }else
        {
            return response()->json(['status' => 0, 'error' => 'Only Admin Can Have Access !']);
        }
    }

    public function packages(Request $request)
    {

        $packages = DB::table('packages')->get();

        return view('dashboard.packages.view', compact('packages'));
        
    }
}
