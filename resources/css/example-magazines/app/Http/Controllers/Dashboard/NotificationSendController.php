<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class NotificationSendController extends Controller
{
    public function index()
    {
        return view('dashboard.notifications.notify');
    }

    public function updateDeviceToken(Request $request, $type)
    {
        if ($type === 'Allow') {
            Auth::user()->transaction_id = $request->token;

            Auth::user()->save();

            return response()->json(['Allow' => 'Notification Allow, Token successfully stored.']);
        }elseif($type === 'Block'){
            Auth::user()->transaction_id = null;

            Auth::user()->save();

            return response()->json(['Block' => 'Notification Block']);
        }else{
            return response()->json(['error' => 'Try Again !']);
        }
    }

    public function sendNotification(Request $request)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotNull('transaction_id')->pluck('transaction_id')->all();
            
        $serverKey = 'AAAANufxUKo:APA91bGGhiY8ZwKAglzBTHh7DXJCmqtEjNEQzFR7v4Mds8LScFdxFMKu34DL8UeRoew_i-04ckWbIq9RjE-j2BCxc5IVZnFeeK9fPgPjppKypaqYbqjlQshFhD01KSjTz5ZRNJAWkdwR'; // ADD SERVER KEY HERE PROVIDED BY FCM
    
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $request->title,
                "body" => $request->body,
                "link" => 'http://localhost:8000/articles/'  
            ]
        ];
        $encodedData = json_encode($data);
    
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
        // FCM response
        dd($result);
    }
}
