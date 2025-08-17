<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Helper;
use DataTables;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $data = DB::table('contact_us');

        $contacts = $data->get();

        if ($request->ajax())
        {

            if (!empty($request->keyword))
            {
                $keyword = trim($request->keyword);
                
                $data = $data->where(function($qry) use ($keyword) {
                                $qry->orWhereRaw("name like ?", ["%{$keyword}%"])
                                ->orWhereRaw("subject like ?", ["%{$keyword}%"])
                                ->orWhereRaw("email like ?", ["%{$keyword}%"]);
                        });
            }
           
            $data = $data->select('*');

            return DataTables::of($data)
                ->addColumn('subject', function ($row) {
                    return '<span style="max-height: 100px; max-width: 100px; overflow: auto; white-space: nowrap;">'.$row->subject.'</span>';
                })
               ->addColumn('message', function ($row) {
                    return '<div style="max-height: 100px; max-width: 280px; overflow: auto; white-space: nowrap;"><span>'.$row->message.'</span></div>';
                })


                ->addColumn('action', function ($row) {
                    $edit_url = route('contact.edit', $row->id);
                    $delete_url = route('contact.remove', $row->id);

                    return '<div class="btn-group">
                                <button type="button" class="btn border-0 p-1 m-0 bg-danger text-white rounded" data-bs-toggle="dropdown" data-bs-target="#dropdown">. . .</button>
                                <div id="dropdown" class="dropdown-menu">
                                    <button data-url="' . $edit_url . '" class="dropdown-item edit_magazine" type="button"><i data-feather="send"> Send Mail</button>
                                    <a class="dropdown-item show_alert" data-url="' . $delete_url . '"><i data-feather="trash-2"></i> Delete</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['subject', 'message', 'action'])
                ->make(true);

        }

        return view('dashboard.contact_us.view', compact('contacts'));
    }

    public function edit(Request $request, $id)
    {
        if (request()->method() === 'GET') {
            $contact = DB::table('contact_us')->where('id', $id)->first();
            return response(['status' => 1, 'modal' => view('dashboard.contact_us.modal', compact('contact', 'id'))->render()]);
        }

        if (auth()->user()->is_admin != 1) {
            return response()->json(['status' => 0, 'error' => 'Only Admin Have Access !']);
        }


        $validator = validator::make($request->all(), [

            'to' => 'required',
            'subject' => 'required',
            'message' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'Validator' => $validator->errors()]);
        }

        $is_send = Helper::send_mail($request->to, $request->subject, $request->message);

        if ($is_send) {
            return response()->json(['status' => 1, 'success' => 'Mail Send Successfully']);
        }

        return response()->json(['status' => 0, 'error' => 'Please Try Again !']);
    }

    public function remove(Request $request, $id)
    {
        if (Auth::user()->is_admin != 1) {

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Only Admin Can Have Access !'], 500);
            }
            return redirect()->back()->with('error', 'Only Admin Can Have Access !');

        }

        $del_contact = DB::table('contact_us')->where('id', $id)->delete();

        if ($del_contact) {
            
            return redirect()->back()->with('success', 'Contact deleted successfully');
        }

        return redirect()->back()->with('error', 'Please Try Again !');
          
    }
}
