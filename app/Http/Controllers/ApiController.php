<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ApiController extends Controller
{
    public function user(Request $request)
    {
        $user = User::all();

        return response()->json(['status' => 200, 'user' => $user]);
    }
}
