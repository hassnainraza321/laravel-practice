<?php

namespace App\Helpers;

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
use App\Models\User;
use App\Models\Image as ImageModel;
use App\Models\Template;
use App\Models\Project;
use App\Models\Country;
use App\Models\TemplateCallToAction;

class Helper
{
    public static $version = '1.0.0';
    public static $css_asset_version = '1.0.0';
    public static $js_asset_version = '1.0.0';
    public static $images_asset_version = '1.0.0';
    public static $currency_symbol = '';
    public static $campaign_broadcast_cost = 0.06;
    public static $campaign_csv_broadcast_cost = 0.01;
    public static $allow_regular_message = 0;

    public static function getSiteTitle($title = null, $with_title = true, $seaprator = ' - ')
    {
        $app_name = config('app.name');

        if (empty($app_name))
        {
            return $title;
        }

        if ($title)
        {
            return $with_title ? $title . $seaprator . $app_name : $title;
        }
        
        return $app_name;
    }

    public static function sendEmail($view, $data = [], $to, $subject, $attachments = array(), $from = null, $email_title_name = null)
    {
        try
        {
            Mail::send($view, $data, function($message) use ($to, $subject, $attachments, $from, $email_title_name) {

                if (!empty($attachments))
                {
                    foreach ($attachments as $key => $attachment)
                    {
                        $file = $attachment['file'];

                        $name = null;
                        $ext = null;

                        if (isset($attachment['name']) && $attachment['ext'])
                        {
                            $name = $attachment['name'] . '.' . $attachment['ext'];
                            $ext = $attachment['ext'];
                        }

                        if (!empty($name))
                        {
                            $message->attach($file, array(
                                    'as' => $name,
                                    'mime' => $ext
                                )
                            );
                        }
                        else
                        {
                            $message->attach($file);
                        }
                    }
                }

                if (empty($from))
                {
                    $from = config('mail.from.address');
                }

                if (empty($email_title_name))
                {
                    $email_title_name = config('mail.from.name');
                }

                if (!empty($from) && !empty($email_title_name))
                {
                    $message->from($from, $email_title_name);
                }

                $message->to($to);
                $message->subject($subject);
            });
        }
        catch(\Exception $e)
        {
            report($e);
        }
    }

    public static function getRandomKey($length = 30)
    {
        return bin2hex(openssl_random_pseudo_bytes($length));
    }

    public static function createSlug($name)
    {
        return Str::slug($name);
    }

    public static function getSlug($name, $table = null, $id = null)
    {
        $slug = Str::slug($name);
        
        $data = DB::table($table)->where('slug', $slug)->where('id', '!=', $id)->first();

        if (!empty($data))
        {
            return $slug . '-' . $id;
        }

        return $slug;
    }

    public static function getUUID($table = null)
    {
        $uuid = (string) Str::uuid();

        if (!empty($table))
        {
            $data = 1;

            while (!empty($data))
            {
                $uuid = (string) Str::uuid();
                $data = DB::table($table)->where('reference_id', $uuid)->first();
            }
        }

        return $uuid;
    }

    public static function getInputValue($key = null, $value = null, $is_collection = true, $default = null)
    {
        if (empty($key) && empty($value))
        {
            return $default;
        }

        if (old($key))
        {
            return old($key);
        }

        if (!empty($value))
        {
            if ($is_collection)
            {
                if (is_array($value) && isset($value[$key]))
                {
                    return $value[$key];
                }
                elseif (is_object($value) && isset($value->$key))
                {
                    return $value->$key;
                }
            }
            else
            {
                return $value;
            }
        }

        return $default;
    }

    public static function storeUploadedFile($file, $target_dir = null, $name = null, $full_path = true, $disk = 'public')
    {
        $extension = '.' . strtolower($file->getClientOriginalExtension());

        if (empty($name))
        {
            $name = self::getRandomKey() . $extension;

            while (empty($name) || Storage::disk($disk)->exists($target_dir . $name))
            {
                $name = self::getRandomKey() . $extension;
            }
        }
        else
        {
            $name .= $extension;
        }

        if ($disk == 'public')
        {
            $file->move(storage_path('app/public/' . $target_dir), $name);
        }
        else
        {
            $file->move(storage_path('app/' . $target_dir), $name);
        }

        if ($full_path)
        {
            return $target_dir . $name;
        }

        return $name;
    }

    public static function getIp()
    {
        $ip = null;

        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key)
        {
            if (array_key_exists($key, $_SERVER) === true)
            {
                foreach (explode(',', $_SERVER[$key]) as $ip)
                {
                    $ip = trim($ip);

                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false)
                    {
                        if (empty($ip))
                        {
                            $ip = '127.0.0.1';
                        }
                    }
                }
            }
        }

        return $ip;
    }

    public static function createUpdateOption($option_key, $option_value = null)
    {
        if (is_array($option_value) || is_object($option_value))
        {
            $option_value = serialize($option_value);
        }

        $meta = DB::table('options')->where('option_key', $option_key)->first();

        if (empty($meta))
        {
            return DB::table('options')->insertGetId( [
                'option_key' => $option_key,
                'option_value' => $option_value,
                'created_at' => now(),
                'updated_at' => now(),
            ] );
        }

        DB::table('options')->where('option_key', $option_key)->update( [
            'option_value' => $option_value,
            'updated_at' => now(),
        ] );

        return;
    }

    public static function deleteOption($option_key)
    {
        DB::table('options')->where('option_key', $option_key)->delete();
    }

    public static function getOption($option_key)
    {
        $option = DB::table('options')->where('option_key', $option_key)->first();

        if (empty($option) || empty($option->option_value))
        {
            return null;
        }

        $option_value = @unserialize($option->option_value);

        if ($option_value !== false)
        {
            return $option_value;
        }

        return $option->option_value;
    }

    public static function displayPrice($price, $settings = null, $add_dash_on_zero = false)
    {
        if (!is_numeric($price))
        {
            return;
        }

        if ($add_dash_on_zero && $price == 0)
        {
            return '-';
        }

        if (empty($settings))
        {
            $settings = Helper::getOption('settings');
        }

        $price = number_format($price, 2, '.', ',');

        return isset($settings['store_currency_format']) && !empty($settings['store_currency_format']) ? str_replace('[AMOUNT]', $price, $settings['store_currency_format']) : '$' . $price;
    }

    public static function getListOfTimezone()
    {
        $timezones = \DateTimeZone::listIdentifiers();

        $all_zones = [];

        foreach($timezones as $timezone)
        {
            $timezone_label = isset(explode('/', $timezone)[1]) ? explode('/', $timezone)[1] : null;
            $timezone_continent = explode('/', $timezone)[0];

            if (!empty($timezone_label))
            {
                $all_zones[$timezone_continent][$timezone] = $timezone_label;
            }
        }

        return $all_zones;
    }

    public static function displayNumber($price, $decimal = 2, $separator = ',')
    {
        if (!is_numeric($price))
        {
            return $price;
        }

        return number_format($price, $decimal, '.', $separator);
    }

    public static function getDatatables($table_data, $with_select_all = 1, $class = 'nowrap')
    {
        $table = '<div class="table-responsive">
                    <table class="table w-100 datatable '. $class .'">
                        <thead>
                            <tr>';

        if (!empty($with_select_all))
        {
            $table .= '<th>
                            <div class="form-check form-checkbox-dark">
                                <input type="checkbox" class="form-check-input select-all-checkbox" id="select-all-checkbox">
                                <label class="form-check-label" for="select-all-checkbox">&nbsp;</label>
                            </div>
                        </th>';
        }

        foreach ($table_data as $key => $th)
        {
            $table .= '<th>' . __($th) . '</th>';
        }

        $total_columns = count($table_data);

        if (!empty($with_select_all))
        {
            $total_columns++;
        }

        $table .= '</tr>
                </thead>
                <tbody><tr colspan="'. $total_columns .'" class="text-center"><td>Loading...</td></tr></tbody>
            </table>
        </div>';

        return $table;
    }

    public static function generateAvatarSVG($name, $width = 32, $length = 1, $font_size = 0.35, $color = '#6c757d', $background = '#f1f1f1')
    {
        $avatar = new \LasseRafn\InitialAvatarGenerator\InitialAvatar();
        return $avatar->name(trim($name))->length($length)->fontSize($font_size)->color($color)->background($background)->rounded()->width($width)->generateSvg()->toXMLString();
    }

    public static function allowedImagesExtensions()
    {
        return ['png', 'jpg', 'gif', 'jpeg', 'webp', 'svg'];
    }

    public static function downloadImage($url)
    {
        if (!isset(pathinfo($url)['extension']) || empty(pathinfo($url)['extension']) || !in_array(strtolower(pathinfo($url)['extension']), self::allowedImagesExtensions()))
        {
            return;
        }

        $image_content = @file_get_contents($url);

        if ($image_content === false)
        {
            return;
        }

        $name = self::getRandomKey() . '.' . pathinfo($url)['extension'];

        while (empty($name) || Storage::disk('public')->exists('images/' . date('Y') . '/' . $name))
        {
            $name = self::getRandomKey() . '.' . pathinfo($url)['extension'];
        }

        Storage::disk('public')->put('images/' . date('Y') . '/' . $name, $image_content);

        return self::createImage(pathinfo($url)['basename'], 'images/' . date('Y') . '/' . $name);
    }

    public static function createImage($image_file_name, $path)
    {
        $size = getimagesize(storage_path('app/public/' . $path));

        $img = new ImageModel();
        $img->image_file_name = $image_file_name;
        $img->image_type = pathinfo(storage_path('app/public/' . $path))['extension'];
        $img->image_width = $size[0];
        $img->image_height = $size[1];
        $img->image_size = Storage::disk('public')->size($path);
        $img->image_path = $path;
        $img->saved_image_file_name = pathinfo(storage_path('app/public/' . $path))['basename'];
        $img->reference_id = self::getUUID('images');
        $img->created_by = auth()->check() ? auth()->user()->id : null;
        $img->ip = self::getIp();
        $img->user_agent = request()->header('User-Agent');
        $img->save();

        return $img;
    }

    public static function removeImage($img = null, $img_reference_id = null, $path = null)
    {
        if (!empty($path) && Storage::disk('public')->exists($path))
        {
            Storage::disk('public')->delete($path);
        }

        if (empty($img) && empty($img_reference_id))
        {
            return;
        }

        if (empty($img_reference_id))
        {
            $img_reference_id = $img->reference_id;
        }
        elseif (empty($img))
        {
            $img = ImageModel::where('reference_id', $img_reference_id)->first();

            if (empty($img))
            {
                return;
            }
        }

        ImageModel::where('reference_id', $img_reference_id)->delete();
        
        if (!empty($img) && !empty($img->image_path) && Storage::disk('public')->exists($img->image_path))
        {
            Storage::disk('public')->delete($img->image_path);
        }

        return;
    }

    public static function formatBytes($size, $precision = 2)
    { 
        $base = log($size) / log(1024);
        $suffix = array("", "KB", "MB", "GB", "TB");
        $f_base = floor($base);
        
        return round(pow(1024, $base - floor($base)), 1) . ' ' . $suffix[$f_base]; 
    }

    public static function getProjectId()
    {
        return DB::table('projects')->where('reference_id', session('ref_id'))->where('is_active', 1)->value('id');
    }

    public static function getAccessTokenFromMeta($code)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/oauth/access_token?client_id=' . self::getOption('meta_app_id') . '&client_secret=' . self::getOption('meta_app_secret') . '&code=' . $code,

          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if (empty($response))
        {
            return;
        }

        $data = json_decode($response, true);

        if (isset($data['access_token']) && !empty($data['access_token'])) 
        {
            return $data['access_token'];
        }

        return;
    }

    public static function getAccountDetailsFromMeta($access_token, $whatsapp_business_account_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/'. $whatsapp_business_account_id .'/phone_numbers?access_token=' . $access_token,

          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if (empty($response))
        {
            return;
        }

        $data = json_decode($response, true);

        if (isset($data['data']) && !empty($data['data']))
        {
            return $data['data'];
        }

        return;
    }

    public static function subscribeToWebhookOnMeta($access_token, $whatsapp_business_account_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/'. $whatsapp_business_account_id .'/subscribed_apps',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $access_token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        if (empty($response))
        {
            return false;
        }

        $data = json_decode($response, true);

        if (isset($data['success']) && $data['success'])
        {
            return true;
        }

        return false;
    }

    public static function accountReviewStatusFromMeta($access_token, $whatsapp_business_account_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/'. $whatsapp_business_account_id .'?fields=account_review_status',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $access_token
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        if (empty($response))
        {
            return;
        }

        $data = json_decode($response, true);

        if (isset($data['account_review_status']) && !empty($data['account_review_status']))
        {
            return $data['account_review_status'];
        }

        return;
    }

    public static function syncTemplatesFromMeta($project)
    {
        $meta_templates = self::fetchTemplatesFromMeta($project);
        
        if (empty($meta_templates)) 
        {
            return response()->json(['status' => -1, 'message' => 'No templates found in Meta API']);
        }

        foreach ($meta_templates as $meta_template) 
        {
            $existing_template = Template::where('meta_template_id', $meta_template['id'])->first();
            
            if (!$existing_template) 
            {
                $template = new Template();
                $template->project_id = Helper::getProjectId() ?? $project->id;
                $template->reference_id = Helper::getUUID('templates', 'reference_id');
                $template->created_by = auth()->user()->id ?? $project->user_id;
            } 
            else 
            {
                $template = $existing_template;
            }

            $template->meta_template_id = $meta_template['id'];
            $template->name = str_replace(' ', '_', strtolower($meta_template['name']));
            $template->category_id = self::mapCategory($meta_template['category']);
            $template->type_id = self::mapCategory($meta_template['category']) == 1 ? 5 : 1;
            $template->template_language_id = self::getLanguageId($meta_template['language']);
            $template->status = $meta_template['status'] ?? 'PENDING';

            if (!empty($meta_template['status'])) 
            {
                if ($meta_template['status'] == 'PENDING') 
                {
                    $template->health = 'Medium';
                }
                else if ($meta_template['status'] == 'APPROVED')
                {
                    $template->health = 'High';
                }
                else if ($meta_template['status'] == 'REJECTED')
                {
                    $template->health = 'Low';
                }
            }

            $template->save();
            
            foreach ($meta_template['components'] as $component) 
            {
                $sample_value = $component['example']['body_text'][0] ?? null;

                if (!empty($sample_value)) 
                {
                    $template->sample_value = json_encode($sample_value);
                }

                if ($component['type'] == 'BODY') 
                {
                    $template->content = $component['text'] ?? null;
                    $template->security_disclaimer = $component['add_security_recommendation'] ?? 0;
                } 
                elseif ($component['type'] == 'FOOTER') 
                {
                    $template->footer_text = $component['text'] ?? null;
                    $template->expiration_warning = $component['code_expiration_minutes'] ?? null;
                }
                elseif ($component['type'] == 'BUTTONS') 
                {
                    // $template->quick_reply = json_encode($component['buttons'] ?? []);
                    foreach ($component['buttons'] as $key => $call_to_action) 
                    {
                        $del_call_to_action = TemplateCallToAction::where('template_id', $template->id)->delete();

                        $action = new TemplateCallToAction();

                        $action->template_id = $template->id;
                        $action->reference_id = Helper::getUUID('template_call_to_actions', 'reference_id'); 
                        $action->created_by = auth()->user()->id ?? $project->user_id;

                        if ($call_to_action['type'] == 'QUICK_REPLY') 
                        {
                            $action->type = 'Quick Reply';
                        }
                        else if ($call_to_action['type'] == 'URL' || $call_to_action['type'] == 'CATALOG')
                        {
                            $action->type = 'Coupon Code';
                        }
                        else
                        {
                            $action->type = '';
                        }
                        
                        $action->button_title = $call_to_action['text'];

                        $action->save();

                    }
                }
            }

            $template->save();
        }

        return response()->json(['status' => 1, 'message' => 'Templates synced successfully!']);
    }

    public static function fetchTemplatesFromMeta($project)
    {
        $url = "https://graph.facebook.com/" . self::getOption('meta_api_version') . "/" . $project->whatsapp_business_account_id . "/message_templates";
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $project->access_token,
                'Content-Type: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($response, true);

        return $data['data'] ?? [];
    }

    public static function mapCategory($meta_category)
    {
        $category = DB::table('template_categories')->where('name', $meta_category)->first();

        return $category->id ?? null;
    }

    public static function getLanguageId($languageCode)
    {
        $language = DB::table('countries')->where('language_code', $languageCode)->where('is_active', 1)->first();
        return $language ? $language->id : null;
    }


    public static function getTemplateFromMeta($project)
    {
        if (empty($project)) 
        {
            return;
        }

        $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $project->whatsapp_business_account_id . '/message_templates';

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $project->access_token,
            ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        $data = json_decode($response, true);

        dd($data);

        if (isset($data['success']) && $data['success']) {
            return true;
        }

        return false;
    }

    public static function deleteTemplateFromMeta($project, $template)
    {
        if (empty($project) && empty($template)) 
        {
            return;
        }

        $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $project->whatsapp_business_account_id . '/message_templates?hsm_id=' . urlencode($template->meta_template_id) . '&name=' . urlencode($template->name);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $project->access_token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        $data = json_decode($response, true);

        if (isset($data['success']) && $data['success']) {
            return true;
        }

        return false;
    }

    public static function authenticationTemplateFromMeta($data, $project, $template)
    {
        if (empty($data->name) && empty($data->expiration_warning) && empty($data->coupon_code)) 
        {
            return;
        }

        if (!empty($data->template_language_id)) 
        {
            $country = DB::table('countries')->where('id', $data->template_language_id)->where('is_active', 1)->first();
        }

        $value = [
            'name' => str_replace(' ', '_', strtolower($data->name)),
            'language' => $country->language_code ?? 'en_US',
            'category' => 'AUTHENTICATION',
            'components' => [
                [
                    'type' => 'BODY',
                    'add_security_recommendation' => true,
                ],
                [
                    'type' => 'FOOTER',
                    'code_expiration_minutes' => $data->expiration_warning,
                ],
                [
                    'type' => 'BUTTONS',
                    'buttons' => [
                        [
                            'type' => 'OTP',
                            'otp_type' => 'COPY_CODE',
                            'text' => $data['coupon_code'][0],
                        ],
                    ],
                ],
            ],
        ];

        $curl = curl_init();

        if (empty($template)) 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $project->whatsapp_business_account_id . '/message_templates';
        } 
        else 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $template->meta_template_id;
        }

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($value),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $project->access_token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        if (empty($response))
        {
            return;
        }

        $data = json_decode($response, true);

        if (isset($data['id']) && !empty($data['id']))
        {
            return $data;
        }

        return;
    }

    public static function marketingTemplateFromMeta($data, $project, $template)
    {
        if (empty($data->name) && empty($data->content) && empty($data->footer_text)) 
        {
            return;
        }

        if (!empty($data->template_language_id)) 
        {
            $country = DB::table('countries')->where('id', $data->template_language_id)->where('is_active', 1)->first();
        }

        $value = [
            'name' => str_replace(' ', '_', strtolower($data->name)),
            'language' => $country->language_code ?? 'en_US',
            'category' => 'MARKETING',
            'components' => [
                [
                    'type' => 'BODY',
                    'text' => $data->content,
                    'example' => [
                        'body_text' => [
                            json_encode($data->sample_value)
                        ]
                    ]
                ],
                [
                    'type' => 'FOOTER',
                    'text' => $data->footer_text
                ],
                [
                    'type' => 'BUTTONS',
                    'buttons' => [
                        [
                            'type' => 'CATALOG',
                            'text' => 'View catalog'
                        ]
                    ]
                ]
            ]
        ];

        $curl = curl_init();

        if (empty($template)) 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $project->whatsapp_business_account_id . '/message_templates';
        } 
        else 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $template->meta_template_id;
        }

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($value),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $project->access_token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        if (empty($response))
        {
            return;
        }

        $data = json_decode($response, true);

        if (isset($data['id']) && !empty($data['id']))
        {
            return $data;
        }

        return;
    }

    public static function marketingTextTemplateFromMeta($data, $project, $template)
    {
        if (empty($data->name) && empty($data->header_text) && empty($data->content) && empty($data->footer_text) && empty($data->quick_reply)) 
        {
            return;
        }

        if (!empty($data->template_language_id)) 
        {
            $country = DB::table('countries')->where('id', $data->template_language_id)->where('is_active', 1)->first();
        }

        $buttons = [];

        foreach ($data->quick_reply as $value) 
        {
            $buttons[] = [ 
                'type' => 'QUICK_REPLY',
                'text' => $value,
            ];
        }
  
        $value = [
            'name' => str_replace(' ', '_', strtolower($data->name)),
            'language' => $country->language_code ?? 'en_US',
            'category' => 'MARKETING',
            'components' => [
                [
                    'type' => 'HEADER',
                    'format' => 'TEXT',
                    'text' => $data->header_text,
                    'example' => [
                        'header_text' => [
                            "Summer Sale"
                        ]
                    ]
                ],
                [
                    'type' => 'BODY',
                    'text' => $data->content,
                    'example' => [
                        'body_text' => [
                            json_encode($data->sample_value)
                        ]
                    ]
                ],
                [
                    'type' => 'FOOTER',
                    'text' => $data->footer_text
                ],
                [
                    'type' => 'BUTTONS',
                    'buttons' => $buttons
                ]
            ]
        ];

        $curl = curl_init();

        if (empty($template)) 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $project->whatsapp_business_account_id . '/message_templates';
        } 
        else 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $template->meta_template_id;
        }

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($value),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $project->access_token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        if (empty($response))
        {
            return;
        }

        $data = json_decode($response, true);

        if (isset($data['id']) && !empty($data['id']))
        {
            return $data;
        }

        return;
    }

    public static function utilityLocationTemplateFromMeta($data, $project, $template)
    {
        if (empty($data->name) && empty($data->content) && empty($data->footer_text) && empty($data->quick_reply)) 
        {
            return;
        }

        if (!empty($data->template_language_id)) 
        {
            $country = DB::table('countries')->where('id', $data->template_language_id)->where('is_active', 1)->first();
        }

        $buttons = [];

        foreach ($data->quick_reply as $value) 
        {
            $buttons[] = [ 
                'type' => 'QUICK_REPLY',
                'text' => $value,
            ];
        }

        $value = [
            'name' => str_replace(' ', '_', strtolower($data->name)),
            'language' => $country->language_code ?? 'en_US',
            'category' => 'UTILITY',
            'components' => [
                [
                    'type' => 'HEADER',
                    'format' => 'LOCATION'
                ],
                [
                    'type' => 'BODY',
                    'text' => $data->content,
                    'example' => [
                        'body_text' => [
                            json_encode($data->sample_value)
                        ]
                    ]
                ],
                [
                    'type' => 'FOOTER',
                    'text' => $data->footer_text
                ],
                [
                    'type' => 'BUTTONS',
                    'buttons' => $buttons
                ]
            ]
        ];

        $curl = curl_init();

        if (empty($template)) 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $project->whatsapp_business_account_id . '/message_templates';
        } 
        else 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $template->meta_template_id;
        }

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($value),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $project->access_token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        if (empty($response))
        {
            return;
        }

        $data = json_decode($response, true);

        if (isset($data['id']) && !empty($data['id']))
        {
            return $data;
        }

        return;
    }

    public static function templateMediaFromMeta($project, $path, $mime_type) 
    {
        if (empty($project) || empty($path) || empty($mime_type)) 
        {
            return;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $project->whatsapp_business_account_id . '/media',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'file' => new \CURLFile($path, $mime_type),
                'type' => $mime_type,
                'messaging_product' => 'whatsapp'
            ),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $project->access_token,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }

    public static function marketingImageTemplateFromMeta($data, $project, $template)
    {
        if (empty($data->name) && empty($data->content) && empty($data->footer_text) && empty($data->type) && empty($data->button_title) && empty($data->button_value)) 
        {
            return;
        }

        if (!empty($data->template_language_id)) 
        {
            $country = DB::table('countries')->where('id', $data->template_language_id)->where('is_active', 1)->first();
        }

        $buttons = [];

        foreach ($data->type as $key => $value) 
        {
            if ($value == 'Phone Number') 
            {
                $buttons[] = [ 
                    'type' => 'PHONE_NUMBER',
                    'text' => $data->button_title[$key],
                    'phone_number' => $data->button_value[$key],
                ];
            }

            if ($value == 'URL') 
            {
                $buttons[] = [ 
                    'type' => 'URL',
                    'text' => $data->button_title[$key],
                    'url' => $data->button_value[$key],
                    'example' => [
                        'summer2023'
                    ]
                ];
            }
        }

        $value = [
            'name' => str_replace(' ', '_', strtolower($data->name)),
            'language' => $country->language_code ?? 'en_US',
            'category' => 'MARKETING',
            'components' => [
                [
                    'type' => 'HEADER',
                    'format' => 'IMAGE',
                    'example' => [
                        'header_handle' => [
                            "4::aW..."
                        ]
                    ]
                ],
                [
                    'type' => 'BODY',
                    'text' => $data->content,
                    'example' => [
                        'body_text' => [
                            json_encode($data->sample_value)
                        ]
                    ]
                ],
                [
                    'type' => 'FOOTER',
                    'text' => $data->footer_text
                ],
                [
                    'type' => 'BUTTONS',
                    'buttons' => $buttons
                ]
            ]
        ];

        $curl = curl_init();

        if (empty($template)) 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $project->whatsapp_business_account_id . '/message_templates';
        } 
        else 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $template->meta_template_id;
        }

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($value),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $project->access_token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        if (empty($response))
        {
            return;
        }

        $data = json_decode($response, true);

        if (isset($data['id']) && !empty($data['id']))
        {
            return $data;
        }

        return;
    }

    public static function utilityDocumentTemplateFromMeta($data, $project, $template)
    {
        if (empty($data->name) && empty($data->content) && empty($data->footer_text) && empty($data->type) && empty($data->button_title) && empty($data->button_value)) 
        {
            return;
        }

        if (!empty($data->template_language_id)) 
        {
            $country = DB::table('countries')->where('id', $data->template_language_id)->where('is_active', 1)->first();
        }

        $buttons = [];

        foreach ($data->type as $key => $value) 
        {
            if ($value == 'Phone Number') 
            {
                $buttons[] = [ 
                    'type' => 'PHONE_NUMBER',
                    'text' => $data->button_title[$key],
                    'phone_number' => $data->button_value[$key],
                ];
            }

            if ($value == 'URL') 
            {
                $buttons[] = [ 
                    'type' => 'URL',
                    'text' => $data->button_title[$key],
                    'url' => $data->button_value[$key],
                ];
            }
        }

        $value = [
            'name' => str_replace(' ', '_', strtolower($data->name)),
            'language' => $country->language_code ?? 'en_US',
            'category' => 'UTILITY',
            'components' => [
                [
                    'type' => 'HEADER',
                    'format' => 'DOCUMENT',
                    'example' => [
                        'header_handle' => [
                            "4::YXBwbGljYXRpb24vcGRm:ARZVv4zuogJMxmAdS3_6T4o_K4ll2806avA7rWpikisTzYPsXXUeKk0REjS-hIM1rYrizHD7rQXj951TKgUFblgd_BDWVROCwRkg9Vhjj-cHNQ:e:1681237341:634974688087057:100089620928913:ARa1ZDhwbLZM3EENeeg"
                        ]
                    ]
                ],
                [
                    'type' => 'BODY',
                    'text' => $data->content,
                    'example' => [
                        'body_text' => [
                            json_encode($data->sample_value)
                        ]
                    ]
                ],
                [
                    'type' => 'FOOTER',
                    'text' => $data->footer_text
                ],
                [
                    'type' => 'BUTTONS',
                    'buttons' => $buttons
                ]
            ]
        ];

        $curl = curl_init();

        if (empty($template)) 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $project->whatsapp_business_account_id . '/message_templates';
        } 
        else 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $template->meta_template_id;
        }

        curl_setopt_array($curl, array(

            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($value),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $project->access_token,
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        if (empty($response))
        {
            return;
        }

        $data = json_decode($response, true);

        if (isset($data['id']) && !empty($data['id']))
        {
            return $data;
        }

        return;
    }

    public static function runBroadcastCampaignFromMeta($request, $contacts, $template_id = null)
    {
        $project = Project::where('id', Helper::getProjectId())->where('status', 1)->first();

        if (empty($project)) 
        {
            return;
        }

        if (empty($contacts)) 
        {
            return;
        }

        if (!empty($template_id)) 
        {
            $template = Template::where('id', $template_id)->where('project_id', Helper::getProjectId())->first();
        }
        else
        {
            $template = Template::where('id', $request->template_id)->where('project_id', Helper::getProjectId())->first();
        }

        if (empty($template) && !empty($request->template_message)) 
        {
            $template_message = $request->template_message;
        }

        $language_code = !empty($template->template_language_id) ? Country::where('id', $template->template_language_id)->value('language_code') : 'en' ;

        foreach (is_array($contacts) ? $contacts : $contacts->pluck('whatsapp_number') as $number) 
        {
            $url = 'https://graph.facebook.com/' . self::getOption('meta_api_version') . '/' . $project->phone_number_id . '/messages';
            // $url = 'https://graph.facebook.com/v22.0/398048283387283/messages';

            if (!empty($template)) 
            {
                $data = [
                    "messaging_product" => "whatsapp",
                    "to" => is_array($number) ? $number['whatsapp_number'] : $number,
                    "type" => "template",
                    "template" => [
                        "name" => $template->name,
                        "language" => ["code" => $language_code]
                    ]
                ];
            }
            else
            {
                $data = [
                    "messaging_product" => "whatsapp",
                    "to" => is_array($number) ? $number['whatsapp_number'] : $number,
                    "text" => [
                        "body" => $template_message
                    ]
                ];
            }

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($data),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $project->access_token,
                    'Content-Type: application/json',
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);

            if (empty($response))
            {
                return;
            }

            $data = json_decode($response, true);

            if (isset($data['error'])) 
            {
                return [
                    'status' => -1,
                    'message' => $data['error']['error_user_msg'] ?? $data['error']['message']
                ];
            }

            var_dump($response); die();

            echo "Response for $number: " . $response . PHP_EOL;
        }
    }
}

// curl -X GET "https://graph.facebook.com/me/permissions?access_token=EAAQwDqYH6RcBOZCN0CaBWGVIZAsnisAde3MHDslGASEsPRBlSCC10mCVvHYZCdvMpVkDQeU8uTMffi3qmb5mZCQ2yO2z5rYwTcX2RQb2jUGUtKsBcieiFhY8n1KyEl21TaZAVZAThXbxeP2p55rfMYg7krfC0snArw1kZCBaxTZADCX119O9J4AZCHYB8sdkMJESC9RFHjIPUHVdCpoltIZBuS8ZAVtmvrOTGwekzOFnklkf7wvxScsSpf5ExD5XtUH"

// curl -X GET "https://graph.facebook.com/v17.0/419576484571442/phone_numbers?access_token=EAAQwDqYH6RcBOZCN0CaBWGVIZAsnisAde3MHDslGASEsPRBlSCC10mCVvHYZCdvMpVkDQeU8uTMffi3qmb5mZCQ2yO2z5rYwTcX2RQb2jUGUtKsBcieiFhY8n1KyEl21TaZAVZAThXbxeP2p55rfMYg7krfC0snArw1kZCBaxTZADCX119O9J4AZCHYB8sdkMJESC9RFHjIPUHVdCpoltIZBuS8ZAVtmvrOTGwekzOFnklkf7wvxScsSpf5ExD5XtUH"

// curl -X GET "https://graph.facebook.com/v20.0/376251095575953" -H "Authorization: Bearer EAAQwDqYH6RcBOyKjrAyAt68VTBfImy4tpCOoaRpQfUkdJ4FuhUqVc8yrD1nJ5KPU673vfZB0nmCDdx8R77xwQSldSzlV5VVkOFHVEEKZALjLYcBDgZAuzp94et4LSLYTji9Kqg24tfO0MYXmdh5DUXtXqV7BdCaJxvWHFy51ua3xOe2bCrSX8PxJHyzqCAIRVSjL4HqhJBTpsyQtPQBFu6wC0MZD"

// curl -X GET "https://graph.facebook.com/v17.0/376251095575953/phone_numbers?access_token=EAAQwDqYH6RcBOZCN0CaBWGVIZAsnisAde3MHDslGASEsPRBlSCC10mCVvHYZCdvMpVkDQeU8uTMffi3qmb5mZCQ2yO2z5rYwTcX2RQb2jUGUtKsBcieiFhY8n1KyEl21TaZAVZAThXbxeP2p55rfMYg7krfC0snArw1kZCBaxTZADCX119O9J4AZCHYB8sdkMJESC9RFHjIPUHVdCpoltIZBuS8ZAVtmvrOTGwekzOFnklkf7wvxScsSpf5ExD5XtUH"

