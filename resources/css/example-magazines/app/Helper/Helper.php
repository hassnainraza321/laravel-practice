<?php

namespace App\Helper;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use SoapClient;
use GuzzleHttp\Pool;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Psr7\MultipartStream;
use URL;
use Artisan;
use Image;
use Mail;
use \Carbon\Carbon;
use DB;
use Storage;
use Google\Client;
use App\Models\User;
use Auth;

class Helper
{
	public static $version = '0.9.0';
    public static $css_asset_version = '0.9.0';
    public static $js_asset_version = '0.9.0';
    public static $images_asset_version = '0.9.0';

    public static function getSiteTitle($title = null, $with_title = true, $separator = ' | ')
    {
        $app_name = config('app.name');

        if ($title)
        {
            if ($with_title)
            {
                return $title . $separator . $app_name;
            }
            else
            {
                return $title;
            }
        }
        
        return $app_name;
    }

    public static function getSlug($name, $table, $id)
    {
        $slug = Str::slug($name);
        $data = DB::table($table)->where('slug', $slug)->where('id', '!=', $id)->first();

        if (!empty($data))
        {
            return $slug . '-' . $id;
        }

        return $slug;
    }

    public static function alert_notification($title, $content, $slug)
    {
        
        $_title = substr($title, 0, 100);
        $_content = substr(strip_tags($content), 0, 100);
        $_slug = $slug;

        $url = 'https://fcm.googleapis.com/fcm/send';

        $FcmToken = User::whereNotNull('transaction_id')->pluck('transaction_id')->all();
            
        $serverKey = 'AAAANufxUKo:APA91bGGhiY8ZwKAglzBTHh7DXJCmqtEjNEQzFR7v4Mds8LScFdxFMKu34DL8UeRoew_i-04ckWbIq9RjE-j2BCxc5IVZnFeeK9fPgPjppKypaqYbqjlQshFhD01KSjTz5ZRNJAWkdwR'; // ADD SERVER KEY HERE PROVIDED BY FCM
    
        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $_title,
                "body" => $_content,
                "image" => 'storage/'. Auth::user()->image,
                "link" => 'http://localhost:8000/articles/'.$_slug,
            ],
            "data" => [
                "link" => 'http://localhost:8000/articles/'.$_slug,
            ],
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
        // return $result;
    }

    public static function send_mail($to, $subject, $content)
    {

        $_mail = Mail::raw($content, function ($message) use ($to, $subject) {
            $message->to($to)
                    ->subject($subject);
        });

        if ($_mail) {
            return true;
        }
        
        return false;
    }
}