<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Support\Facades\Validator;
use Helper;

class ContactController extends Controller
{
    public function contact(Request $request)
    {
        if (request()->method() === 'GET') {
            return view('front.contact_us.view');
        }

        $Validator = validator::make($request->all(), [

            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);

        if ($Validator->fails()) {
            return response()->json(['status' => 0, 'Validator' => $Validator->errors()]);
        }


        $contact_us = DB::table('contact_us')->insert([

            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'created_at' => now(),
            'updated_at' => now(),

        ]);

        if ($contact_us) {
            return response()->json(['status' => 1, 'success' => 'Massage Deliverd Successfully']);
        }

        return response()->json(['status' => 0, 'error' => 'Please Try Again !']);
    }
}
