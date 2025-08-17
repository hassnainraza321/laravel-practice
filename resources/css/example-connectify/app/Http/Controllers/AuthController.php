<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use DB;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (auth()->check())
        {
            return redirect()->route('project');
        }

        if (request()->method() == 'GET')
        {
            return view('auth.login');
        }

        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
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

        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password, 'is_active' => 1], $remember))
        {
            return response()->json(['status' => -1, 'message' => ['email' => ['Login Failed. Email/Password is incorrect Or account is inactive.']]]);
        }

        return response()->json(['status' => 1, 'redirect' => route('project')]);
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('login');
    }

    public function register(Request $request)
    {
        if (auth()->check())
        {
            return redirect()->route('project');
        }

        if (request()->method() == 'GET')
        {
            return view('auth.register');
        }

        $validator = \Validator::make($request->all(), [
            'first_name' => 'required',
            'email' => 'required|email|unique:users',
            'whatsapp_number' => 'required|numeric|digits_between:1,15',
            'password' => 'required|confirmed',
        ]);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $user = new User();

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name ?? null;
        $user->display_name = trim($request->first_name . ' ' . ($request->last_name ?? ''));
        $user->email = $request->email;
        $user->phone = $request->whatsapp_number;
        $user->password = bcrypt($request->password);
        $user->role = 0;
        $user->reference_id = Helper::getUUID('users');

        $user->save();

        auth()->login($user);

        return response()->json(['status' => 1, 'redirect' => route('project')]);
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
dd($socialUser);
            $user = User::updateOrCreate([
                'email' => $socialUser->getEmail(),
            ], [
                'name' => $socialUser->getName(),
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'password' => bcrypt(uniqid()),
            ]);

            Auth::login($user);
            
            return redirect()->route('project')->with('success', 'Login successful!');
        } 
        catch (\Exception $e) 
        {
            return redirect()->route('login')->with('error', 'Something went wrong! Try again.');
        }
    }
}
