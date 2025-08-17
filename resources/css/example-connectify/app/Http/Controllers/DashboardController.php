<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->query('ref_id')) 
        {
            session()->forget('ref_id');
            $ref_id = strtolower($request->query('ref_id'));

            if (!session()->has('ref_id')) 
            {
                session(['ref_id' => $ref_id]);
            }
        }
        else
        {
            return redirect()->back();
        }

        return redirect()->route('dashboard');
    }

    public function dashboard(Request $request)
    { 
        return view('dashboard');
    }
}
