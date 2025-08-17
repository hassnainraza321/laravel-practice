<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Auth;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        
        if (auth()->check())
        {
            return redirect()->route('dashboard');
        }

        if (request()->method() == 'GET')
        {
            return view('dashboard.auth.login');
        }

        $validate = $request->validate([

            'email' => 'required',
            'password' => 'required'
        ]);

        $remember = false;

        if (!empty($request->remember_me))
        {
            $remember = true;
        }

        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password], $remember))
        {
            return redirect()->back()->with('error', 'Login Failed. Email/Password is incorrect');
        }

        $user_id = Auth::id();

        $user = User::find($user_id);
 
        // Creating a token without scopes...
        $token = $user->createToken('auth_token')->accessToken;

        $user = DB::table('users')->where('id', $user_id)->update([

            'api_token' => $token

        ]);

        $user_ip = request()->ip();
        $position = Location::get('39.52.223.9');

        $agent = new Agent();

        // Browser information
        $browser = $agent->browser();
        $platform = $agent->platform();
        $is_mobile = $agent->isMobile();

        // Device information
        $device = $agent->device();
        $is_desktop = $agent->isDesktop();

        $device = $is_mobile ? 0 : ($is_desktop ? 1 : null);

        if ($position) {
            
            $user_information = DB::table('user_logins')->insert([

                'user_id' => $user_id,
                'ip_address' => $position->ip,
                'browser' => $browser,
                'platform' => $platform,
                'device' => $device,
                'last_login' => now(),
                'created_at' => now(),
                'updated_at' => now()

            ]);

            
        }

        
        if ($position)
        {
            return response()->json(['status' => 'success', 'token' => $token]);
        }

    }

    public function logout()
    {
        auth()->logout();
        return view('dashboard.auth.logout');
    }

    public function register(Request $request)
    {
        if (auth()->check())
        {
            return redirect()->route('dashboard');
        }

        $validate = $request->validate([

            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:4'
        ]);

        if ($validate) {
            
            $user_id = DB::table('users')->insertGetId([

                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'created_at' => now(),
                'updated_at' => now()

            ]);

            auth()->loginUsingId($user_id);

            $user = User::find($user_id);
     
            // Creating a token without scopes...
            $token = $user->createToken('auth_token')->accessToken;

            $user_ip = request()->ip();
            $position = Location::get('39.52.223.9');

            $agent = new Agent();

            // Browser information
            $browser = $agent->browser();
            $platform = $agent->platform();
            $is_mobile = $agent->isMobile();

            // Device information
            $device = $agent->device();
            $is_desktop = $agent->isDesktop();

            if (!is_null($is_mobile)) {
            
                $device = 0;
            }
            elseif (!is_null($is_desktop)) {

                $device = 1;
            }
            else
            {
                $device = null;
            }

            if ($position) {
                
                $user_information = DB::table('user_logins')->insert([

                    'user_id' => $user_id,
                    'ip_address' => $position->ip,
                    'browser' => $browser,
                    'platform' => $platform,
                    'device' => $device,
                    'last_login' => now(),
                    'created_at' => now(),
                    'updated_at' => now()

                ]);

                
            }
            
            if ($user_id && $position)
            {
                
                return response()->json(['status' => 'success', 'token' => $token]);
               
            }

            return response()->json(['error', 'Please Try Again !']);
        }


    }
}
