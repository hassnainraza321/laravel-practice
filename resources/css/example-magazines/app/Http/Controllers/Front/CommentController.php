<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Helper;

class CommentController extends Controller
{
    public function add(Request $request, $slug)
    {
        
        $validator = validator::make($request->all(), [

            'name' => 'required',
            'email' => 'required',
            'comment' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'Validator' => $validator->errors()]);
        }

        $article_id = DB::table('articles')->where('slug', $slug)->value('id');

        $comment = DB::table('comments')->insert([

            'article_id' => $article_id,
            'name' => $request->name,
            'email' => $request->email,
            'website' => $request->website,
            'comment' => $request->comment,
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        if ($comment) {
            return response()->json(['status' => 1, 'success' => 'Comment Send Successfully']);
        }

        return response()->json(['status' => 0, 'error' => 'Please Try Again !']);
    }
}
