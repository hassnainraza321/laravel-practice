<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;

class MagazineController extends Controller
{
    public function list(Request $request)
    {
        if (Auth::user()->is_admin === 1) {
            if (request()->method() === 'GET') {
            
                $magazines = DB::table('magazines')->get();

                return response()->json(['magazines' => $magazines], 200);

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

            return response()->json(['status' => 1, 'success' => 'Data inserted successfully'],200);

        }else
        {
            return response()->json(['status' => 0, 'error' => 'Only Admin Can Have Access !'], 500);
        }

    }

    public function edit(Request $request, $id, $type = null)
    {
        if (Auth::user()->is_admin === 1) {
            if (request()->method() === 'GET') {
                
                $magazine = DB::table('magazines')->where('id', $id)->first();
                return response()->json(['status' => 1, 'magazine' => $magazine], 200);

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
            $del_magazine = DB::table('magazines')->where('id', $id)->delete();

            if ($del_magazine) {
                    return response()->json(['success' => 'Magazine deleted successfully'], 200);
                }
                return response()->json(['error' => 'Please Try Again !'], 500);

          }else
        {
            return response()->json(['error' => 'Only Admin Can Have Access !'], 500);
        }  
    }
}
