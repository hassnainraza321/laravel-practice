<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;

class FrontController extends Controller
{
    public function front()
    {
        $user = DB::table('users')->where('is_admin', 1)->first();
        
        return view('front.main.main', compact('user'));
    }
}
