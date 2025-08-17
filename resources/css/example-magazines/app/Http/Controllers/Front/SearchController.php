<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->keyword;

        $articles = DB::table('articles')->where('title', 'like', '%' . $keyword . '%')->where('status', 1)->orderBy('id', 'desc')->paginate(10);
        
        return view('front.search.search', compact('articles', 'keyword'));
    }
}
