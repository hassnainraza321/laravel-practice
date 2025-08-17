<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Helpers\Helper;
use DB;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $data = User::where('id', auth()->id())->first();

        if (empty($data))
        {
            return redirect()->back();
        }

        if (request()->method() === 'GET')
        {
            return view('profile.add', compact('data'));
        }

        $validations = [
            'first_name' => 'required',
            'email' => 'required|email',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $data->first_name = $request->first_name;
        $data->last_name = $request->last_name ?? null;
        $data->email = $request->email;
        $data->phone = $request->phone ?? null;

        if ($request->hasFile('image')) 
        {
            if (!empty($data->image)) 
            {
                Storage::disk('public')->delete($data->image);
            }

            $image_path = $request->file('image')->store('images');
            $image = str_replace('public/', '', $image_path);
            $data->image = $image;
        }

        if (!empty($request->last_password) && !empty($request->password)) 
        {
            if (Hash::check($request->last_password, $data->password)) 
            {
                $data->password = bcrypt($request->password);
            }
            else
            {
                return response()->json(['status' => -1, 'message' => array_merge($validator->messages()->toArray(), ['last_password' => ['Last password is incorrect!']])]);
            }
        }

        $data->save();

        return response()->json(['status' => 1, 'message' => 'Data updated successfully.', 'refresh' => true]);
    }
}
