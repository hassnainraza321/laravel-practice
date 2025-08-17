<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Helper;
use DataTables;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->is_admin === 1) {
            $data = DB::table('comments');
        }else{
            $articles = DB::table('articles')->where('user_id', Auth::id())->pluck('id')->toArray();
            $data = DB::table('comments')->whereIn('article_id', $articles);
        }

        $comments = $data->get();

        if ($request->ajax())
        {

            if (!empty($request->keyword))
            {
                $keyword = trim($request->keyword);
                
                $data = $data->where(function($qry) use ($keyword) {
                                $qry->orWhereRaw("name like ?", ["%{$keyword}%"])
                                ->orWhereRaw("website like ?", ["%{$keyword}%"])
                                ->orWhereRaw("email like ?", ["%{$keyword}%"])
                                ->orWhereRaw("comment like ?", ["%{$keyword}%"]);
                        });
            }

            if ($request->status_id !== null)
            {
                $data = $data->where('comments.status', $request->status_id);
            }
           
            $data = $data->select('*');

            return DataTables::of($data)
               ->addColumn('comment', function ($row) {
                    return '<div class="text-center" style="max-height: 100px; max-width: 280px; overflow: auto; white-space: nowrap;"><span>'.$row->comment.'</span></div>';
                })
                ->addColumn('status', function ($row) {
                    $status_class = $row->status === 1 ? 'bg-success' : 'bg-danger';
                    $status_text = $row->status === 1 ? 'Publish' : 'Draft';
                    return '<span class="badge ' . $status_class . '">' . $status_text . '</span>';
                })

                ->addColumn('action', function ($row) {
                    $edit_url = route('comment.edit', $row->id);
                    $mail_reply = route('comment.reply', $row->id);
                    $delete_url = route('comment.remove', $row->id);
                    $publish = route('comment.publish', $row->id);
                    $draft = route('comment.publish', $row->id);

                    $comment_status = DB::table('comments')->where('id', $row->id)->first();

                    $publish_link = '';
                    $draft_link = '';

                    if ($comment_status->status === 0){
                        $publish_link = '<a href="'.$publish.'" class="dropdown-item edit_magazine"> <i data-feather="check"></i> Publish</a>';
                    }
                    else{
                        $draft_link = '<a href="'.$draft.'" class="dropdown-item edit_magazine"> <i data-feather="x"></i> Draft</a>';
                    }

                    return '<div class="btn-group">
                                <button type="button" class="btn border-0 p-1 m-0 bg-danger text-white rounded" data-bs-toggle="dropdown" data-bs-target="#dropdown">. . .</button>
                                <div id="dropdown" class="dropdown-menu">
                                    <button data-url="'.$edit_url.'" class="dropdown-item edit_magazine" type="button" ><i data-feather="edit"></i> Edit</button>
                                    <button data-url="' . $mail_reply . '" class="dropdown-item edit_magazine" type="button"><i data-feather="send"> Send Mail</button>
                                    '.$publish_link .$draft_link.'
                                    <a class="dropdown-item show_alert" data-url="' . $delete_url . '"><i data-feather="trash-2"></i> Delete</a>
                                </div>
                            </div>';
                })
                ->rawColumns(['comment','status', 'action'])
                ->make(true);

        }

        return view('dashboard.comments.view', compact('comments'));
    }

    public function reply(Request $request, $id)
    {
        if (request()->method() === 'GET') {
            $comment = DB::table('comments')->where('id', $id)->first();
            return response(['status' => 1, 'modal' => view('dashboard.comments.modals.mail_modal', compact('comment', 'id'))->render()]);
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

    public function edit(Request $request, $id)
    {
        if (request()->method() === 'GET') {
            $comment = DB::table('comments')->where('id', $id)->first();
            return response(['status' => 1, 'modal' => view('dashboard.comments.modals.edit_modal', compact('comment', 'id'))->render()]);
        }

        if (auth()->user()->is_admin != 1) {
            return response()->json(['status' => 0, 'error' => 'Only Admin Have Access !']);
        }


        $validator = validator::make($request->all(), [

            'name' => 'required',
            'email' => 'required',
            'comment' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'Validator' => $validator->errors()]);
        }

        $comment = DB::table('comments')->where('id', $id)->update([

            'name' => $request->name,
            'email' => $request->email,
            'website' => $request->website,
            'comment' => $request->comment,
            'updated_at' => now(),

        ]);

        if ($comment) {
            return response()->json(['status' => 1, 'success' => 'Comment Update Successfully']);
        }

        return response()->json(['status' => 0, 'error' => 'Please Try Again !']);
    }

    public function publish($id)
    {
        $comment = DB::table('comments')->where('id', $id)->first();

        if ($comment->status === 0) {

            $comment = DB::table('comments')->where('id', $id)->update([

                'status' => 1,

            ]);

            if ($comment) {
                return redirect()->back()->with('success', 'Comment suspend successfully');
            }
            return redirect()->back()->with('error', 'Please Try Again !');

        }elseif ($comment->status === 1){

            $comment = DB::table('comments')->where('id', $id)->update([

                'status' => 0,

            ]);

            if ($comment) {
                return redirect()->back()->with('success', 'Comment active successfully');
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

        $del_comment = DB::table('comments')->where('id', $id)->delete();

        if ($del_comment) {
            
            return redirect()->back()->with('success', 'Comment deleted successfully');
        }

        return redirect()->back()->with('error', 'Please Try Again !');
          
    }
}
