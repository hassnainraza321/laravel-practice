<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class MagazineController extends Controller
{
    public function list($slug)
    {
    	$magazine = DB::table('magazines')->where('slug', $slug)->first();
    	$article_id = DB::table('mag_articles')->where('magazine_id', $magazine->id)->pluck('article_id')->toArray();
    	$articles = DB::table('articles')->whereIn('id', $article_id)->where('status', 1)->orderBy('id', 'desc')->paginate(10);
        
    	return view('front.magazines.list', compact('article_id', 'articles'));
    }
}
