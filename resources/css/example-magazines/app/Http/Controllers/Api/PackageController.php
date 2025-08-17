<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;

class PackageController extends Controller
{
    public function add(Request $request)
    {
        if (Auth::user()->is_admin === 1) {
            if (request()->method() === 'GET') {

                $packages = DB::table('packages')->get();

                return response()->json(['packages' => $packages], 200);

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

                $package = DB::table('packages')->where('id', $id)->first();

                return response()->json(['status' => 1, 'package' => $package], 200);

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

                return response()->json(['status' => 1, 'success' => 'Data updated successfully'], 200);
            }

            return response()->json(['status' => 0, 'error' => 'Please Try Again !'], 500);
        }else
        {
            return response()->json(['status' => 0, 'error' => 'Only Admin Can Have Access !'], 500);
        }
    }

    public function remove(Request $request, $id)
    {
        if (Auth::user()->is_admin === 1) {
            $del_package = DB::table('packages')->where('id', $id)->delete();

            if ($del_package) {
                return response()->json(['success' => 'Package deleted successfully'], 200);
            }
            return response()->json(['error' => 'Please Try Again !'], 500);
        }else
        {
            return response()->json(['error' => 'Only Admin Can Have Access !'], 500);
        }
    }

    public function packages(Request $request)
    {

        $packages = DB::table('packages')->get();

        return response()->json(['packages' => $packages], 200);
    }
}
