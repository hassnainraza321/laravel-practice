<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use DataTables;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        if (request()->method() === 'GET') {
            return view('dashboard.profile.view');
        }

        $Validator = $request->validate([

            'username' => 'required',
            'email' => 'required|email|unique:users,email,' . Auth::id(),

        ]);


        $user = DB::table('users')->where('id', Auth::id())->update([

            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,

        ]);

        if ($request->image) {

            $img = DB::table('users')->where('id', Auth::id())->value('image');

            Storage::disk('public')->delete($img);

            $image_path = $request->file('image')->store('public/images');
            $image = str_replace('public/', '', $image_path);
            $user = DB::table('users')->where('id', Auth::id())->update([

                'image' => $image,

            ]);
        }

        if ($request->last_password && $request->password) {
            
            if (Hash::check($request->last_password, Auth::user()->password)) {
                $user = DB::table('users')->where('id', Auth::id())->update([

                    'password' => bcrypt($request->password),

                ]);
            }else{
                return redirect()->back()->with('error', 'Last password is incorrect !');
            }
        }

        if ($user) {
            return redirect()->back()->with('success', 'User updated successfully');
        }
        return redirect()->back()->with('error', 'Please Try Again !');
    }

    public function user(Request $request)
    {
        $data = DB::table('users')->where('is_admin', 0);

        $users = $data->get();

        if ($request->ajax())
        {

            if (!empty($request->keyword))
            {
                $keyword = trim($request->keyword);
                
                $data = $data->where(function($qry) use ($keyword) {
                                $qry->orWhereRaw("username like ?", ["%{$keyword}%"])
                                ->orWhereRaw("email like ?", ["%{$keyword}%"]);
                        });
                
            }

            if ($request->status_id !== null)
            {
                $data = $data->where('users.account_status', $request->status_id);
            }
           
            $data = $data->select('users.*');

            return DataTables::of($data)
                ->addColumn('account_status', function ($row) {
                    $status_class = $row->account_status === 0 ? 'bg-success' : 'bg-danger';
                    $status_text = $row->account_status === 0 ? 'Active' : 'Suspend';
                    return '<span class="badge ' . $status_class . '">' . $status_text . '</span>';
                })
                ->addColumn('action', function ($row) {
                    $remove_url = route('users.remove', $row->id);
                    $suspend_url = route('users.suspend', $row->id);

                    $user_status = DB::table('users')->where('id', $row->id)->first();

                    $suspend_link = '';
                    $active_link = '';

                    if ($user_status->account_status === 0){
                        $suspend_link = '<a class="dropdown-item" href="' . $suspend_url . '"><i data-feather="user-x"></i> Suspend</a>';
                    }
                    else{
                        $active_link = '<a class="dropdown-item" href="' . $suspend_url . '"><i data-feather="user-check"></i> Active</a>';
                    }

                    return '<div class="btn-group">
                                <button type="button" class="btn border-0 p-1 m-0 bg-danger text-white rounded" data-bs-toggle="dropdown" data-bs-target="#dropdown">. . .</button>
                                <div id="dropdown" class="dropdown-menu">
                                    '.$suspend_link .$active_link.'
                                    <a class="dropdown-item show_alert" data-url="' . $remove_url . '"><i data-feather="trash-2"></i> Delete</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['account_status', 'action'])
                ->make(true);

        }

        return view('dashboard.profile.list', compact('users'));
    }

    public function suspend($id)
    {
        $user = DB::table('users')->where('id', $id)->first();

        if ($user->account_status === 0) {

            $user = DB::table('users')->where('id', $id)->update([

                'account_status' => 1,

            ]);

            if ($user) {
                return redirect()->back()->with('success', 'User suspend successfully');
            }
            return redirect()->back()->with('error', 'Please Try Again !');

        }elseif ($user->account_status === 1){

            $user = DB::table('users')->where('id', $id)->update([

                'account_status' => 0,

            ]);

            if ($user) {
                return redirect()->back()->with('success', 'User active successfully');
            }
            return redirect()->back()->with('error', 'Please Try Again !');

        }else{
            return redirect()->back()->with('error', 'Something wrong, Please Try Again !');
        }


    }

    public function remove(Request $request, $id)
    {
        if (Auth::user()->is_admin != 1) {

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Only Admin Can Have Access !'], 500);
            }
            return redirect()->back()->with('error', 'Only Admin Can Have Access !');
        }  

        $del_user = DB::table('users')->where('id', $id)->delete();

        if ($request->expectsJson()) {
            if ($del_user) {
                return response()->json(['success' => 'User deleted successfully'], 200);
            }
            return response()->json(['error' => 'Please Try Again !'], 500);
        }

        if ($del_user) {
            
            return redirect()->back()->with('success', 'User deleted successfully');
        }

        return redirect()->back()->with('error', 'Please Try Again !');
          
    }
}
