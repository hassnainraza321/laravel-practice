<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class ArticleController extends Controller
{
    public function detail($slug)
    {
        $user = DB::table('users')->where('is_admin', 1)->first();
        $article = DB::table('articles')->where('slug', $slug)->first();
        return view('front.articles.detail', compact('user', 'article'));
    }

    public function all()
    {
        return view('front.articles.list');
    }
}
