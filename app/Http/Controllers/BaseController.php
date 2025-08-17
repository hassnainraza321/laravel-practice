<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Helper;
use DataTables;
use \Carbon\Carbon;

class BaseController extends Controller
{
    public function index(Request $request)
    {
        $data = DB::table('users');

        if ($request->ajax())
        {
            $data = $data->select('users.*');

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
                    ->addColumn('actions', function ($row) {

                        return '<div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-'. $row->id .'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-'. $row->id .'">
                                        <a class="dropdown-item fetch-dynamic-modal" data-url="'. route('user.get', $row->id) .'">Edit</a>
                                        <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="'. $row->id .'">Remove</a>
                                    </div>
                                </div>';
                        
                    })
                    ->rawColumns(['index_data', 'actions'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->whereRaw("name like ?", ["%{$keyword}%"])
                                ->orWhereRaw("email like ?", ["%{$keyword}%"]);
                    })
                    ->order(function ($query) {

                        $query->orderBy('id', 'desc');
                        
                    })
                    ->make(true);
        }

        return view('user');
    }

    public function getUser(Request $request, $id)
    {
        $user = User::where('id', $id)->first();

        if (empty($user)) 
        {
            return response()->json(['status' => -1, 'message' => 'User not found!']);
        }

        if (request()->method() === 'GET') 
        {
            return response()->json(['status' => 1, 'modal' => view('auth.register', ['user' => $user])->render()]);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return response()->json(['status' => 1, 'redirect' => route('user')]);
    }
}
