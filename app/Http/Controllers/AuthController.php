<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\User;
use DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (auth()->check())
        {
            return redirect()->route('user');
        }

        if (request()->method() === 'GET') 
        {
            return response()->json(['status' => 1, 'modal' => view('auth.login')->render()]);
        }

        $validator = \Validator::make($request->all(), [

            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $remember = false;

        if (!empty($request->remember_me))
        {
            $remember = true;
        }

        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password], $remember))
        {
            return response()->json(['status' => -1, 'error_message' => ['email' => ['Login Failed. Email/Password is incorrect Or account is inactive.']]]);
        }

        return response()->json(['status' => 1, 'redirect' => route('user')]);
    }

    public function register(Request $request)
    {
        if (auth()->check())
        {
            return redirect()->route('user');
        }

        if (request()->method() === 'GET') 
        {
            return response()->json(['status' => 1, 'modal' => view('auth.register')->render()]);
        }

        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        auth()->login($user);

        return response()->json(['status' => 1, 'redirect' => route('user')]);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('user');
    }
}
