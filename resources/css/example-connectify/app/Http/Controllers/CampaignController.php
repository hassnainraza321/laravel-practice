<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\Country;
use App\Models\CampaignAudience;
use App\Models\CampaignContact;
use App\Models\CampaignRetry;
use App\Models\CampaignAudienceAttribute;
use DB;
use Excel;
use Storage;
use DataTables;
use \Carbon\Carbon;
use App\Helpers\Helper;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

class CampaignController extends Controller
{
    public function campaignList(Request $request)
    {
        $data = DB::table('campaigns')
                ->leftJoin('templates', 'templates.id', '=', 'campaigns.template_id')
                ->where('campaigns.project_id', Helper::getProjectId())
                ->where('campaigns.id', '!=', 0);
        
        if ($request->ajax())
        {
            $data = $data->select('campaigns.*', 'templates.name as template_name');

            return DataTables::of($data)
                    ->addColumn('index_data', function ($row) {

                        return '<div class="form-check form-checkbox-dark">
                                    <input type="checkbox" class="form-check-input select-item-checkbox" id="select-item-'. $row->id .'" value="'. $row->id .'">
                                    <label class="form-check-label no-rowurl-redirect" for="select-item-'. $row->id .'">&nbsp;</label>
                                </div>';
                        
                    })
                    ->editColumn('created_at', function ($row) {
                        return $row->created_at ? with(new Carbon($row->created_at))->format('d/m/Y') : '';
                    })
                    ->editColumn('audience', function ($row) {
                         
                        return !empty($row->audience) ? $row->audience : 0;
                    })
                    ->editColumn('status', function ($row) {
                         
                        return '<span class="badge text-bg-success">'. $row->status .'</span>';
                    })
                    ->editColumn('template_name', function ($row) {

                        if (empty($row->template_name)) 
                        {
                            $template = $row->template_message;
                        }
                         
                        return $row->template_name ?? $template ?? '-';
                    })
                    ->addColumn('actions', function ($row) {

                        return '<div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-'. $row->id .'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-'. $row->id .'">
                                        <a class="dropdown-item" href="'. route('campaigns.run', $row->id) .'">Run Again</a>
                                        <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="'. $row->id .'">Remove</a>
                                    </div>
                                </div>';
                        
                    })
                    ->rawColumns(['index_data', 'status', 'actions'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->whereRaw("campaigns.campaign like ?", ["%{$keyword}%"])
                            ->orWhereRaw("campaigns.type like ?", ["%{$keyword}%"])
                            ->orWhereRaw("templates.name like ?", ["%{$keyword}%"]);
                    })
                    ->order(function ($query) {

                        if (!empty(request()->sort_by))
                        {
                            $sort_data = explode('-', request()->sort_by);

                            if (isset($sort_data[0]) && !empty($sort_data[0]) && isset($sort_data[1]) && !empty($sort_data[1]) && in_array(strtolower($sort_data[1]), ['asc', 'desc']) && in_array(strtolower($sort_data[0]), ['campaign', 'created_at']))
                            {
                                $query->orderBy(strtolower($sort_data[0]), strtolower($sort_data[1]));
                            }
                            else
                            {
                                $query->orderBy('id', 'desc');
                            }
                        }
                        else
                        {
                            $query->orderBy('id', 'desc');
                        }
                    })
                    ->make(true);
        }

        $is_found = $data->first();

        return view('campaign.list')->with(compact('is_found'));
    }
 
    public function campaignAdd(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }
        
        if (request()->method() === 'GET')
        {
            return view('campaign.broadcast');
        }

        $validations = [
            'campaign_name' => 'required',
        ];

        if (Helper::$allow_regular_message === 1 && isset($request->message_type) && $request->message_type == 1) 
        {
            $validations['template_message'] = 'required';
        }
        else
        {
            $validations['template_id'] = 'required';
        }

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        if (isset($request->campaign_contact) && !empty($request->campaign_contact)) 
        {
            $contacts = Contact::where('project_id', Helper::getProjectId())->whereIn('id', $request->campaign_contact)->get();

        }
        elseif (isset($request->campaign_tag) && !empty($request->campaign_tag)) 
        {
            $contacts = Contact::where('project_id', Helper::getProjectId())->whereIn('tag_id', $request->campaign_tag)->get();

        }
        elseif (isset($request->created_at) && !empty($request->created_at)) 
        {
            if (!empty($request->created_at_from) && !empty($request->created_at_to)) 
            {
                $contacts = Contact::where('project_id', Helper::getProjectId())->whereBetween('created_at', [\Carbon\Carbon::parse($request->created_at_from)->startOfDay(), \Carbon\Carbon::parse($request->created_at_to)->endOfDay()])->get();
            }
        }
        else
        {
            $contacts = Contact::where('project_id', Helper::getProjectId())->get();
        }

        if ($contacts->isEmpty()) 
        {
            return response()->json(['status' => -1, 'message' => 'Contacts not found. Please add it first!.']);
        }

        if (!isset($request->schedule_date_and_time) || empty($request->schedule_date_and_time)) 
        {
            $campaign = Helper::runBroadcastCampaignFromMeta($request, $contacts);

            if (is_array($campaign))
            {
                return response()->json($campaign);
            }
        }

        $campaign = new Campaign();
        $campaign->project_id = Helper::getProjectId();
        $campaign->campaign = $request->campaign_name;
        $campaign->type = 'Broadcast';
        $campaign->message_type = $request->message_type;
        $campaign->template_id = $request->template_id ?? null;
        $campaign->template_message = $request->template_message ?? null; 
        $campaign->username = $request->test_campaign ?? null;
        $campaign->test_campaign = !empty($request->test_campaign) ? 1 : 0;
        $campaign->whatsapp_number = $request->whatsapp_number ?? null;
        
        if (isset($request->schedule_date_and_time) && !empty($request->schedule_date_and_time)) 
        {
            $campaign->schedule_date_and_time = $request->schedule_date_and_time;
            $campaign->schedule_date = $request->schedule_date ?? null;
            $campaign->schedule_time = $request->schedule_time ?? null;
            $campaign->campaign_timezone = $request->campaign_timezone ?? null;
        } 

        $campaign->retry_campaign = $request->retry_campaign ?? 0;
        $campaign->status = 'Live';
        $campaign->reference_id = Helper::getUUID('campaigns', 'reference_id'); 
        $campaign->created_by = auth()->user()->id;

        $campaign->save();

        if (isset($request->last_seen) || isset($request->created_at) || isset($request->optin)) 
        {
            $campaign_audience = new CampaignAudience();

            $campaign_audience->project_id = Helper::getProjectId();
            $campaign_audience->campaign_id = $campaign->id;
            $campaign_audience->last_seen = $request->last_seen ?? null;
            $campaign_audience->last_seen_start_date = $request->last_seen_from ?? null;
            $campaign_audience->last_seen_end_date = $request->last_seen_to ?? null;
            $campaign_audience->created = $request->created_at ?? null;
            $campaign_audience->created_at_start_date = $request->created_at_from ?? null;
            $campaign_audience->created_at_end_date = $request->created_at_to ?? null;
            $campaign_audience->optin = $request->opted_in ?? null;
            $campaign_audience->incoming_blocked = $request->incoming_blocked ?? null;
            $campaign_audience->read_status = $request->read_status ?? null;
            $campaign_audience->reference_id = Helper::getUUID('campaign_audience', 'reference_id');
            $campaign_audience->created_by = auth()->user()->id;

            $campaign_audience->save();
        }

        if (!$contacts->isEmpty()) 
        {
            foreach ($contacts as $key => $contact) 
            {
                $campaign_contact = new CampaignContact();

                $campaign_contact->campaign_id = $campaign->id;
                $campaign_contact->contact_id = $contact->id;
                $campaign_contact->contact_number = $contact->whatsapp_number;

                $campaign_contact->save();
            }
        }

        $contacts = CampaignContact::where('campaign_id', $campaign->id)->count();

        $campaign->audience = !empty($contacts) ? $contacts : 0;
        $campaign->save();

        if (isset($request->attribute) && !empty($request->attribute)) 
        {
            foreach ($request->attribute as $key => $element) 
            {
                if (!empty($element)) 
                {
                    $campaign_attribute = new CampaignAudienceAttribute();

                    $campaign_attribute->project_id = Helper::getProjectId();
                    $campaign_attribute->campaign_audience_id = $campaign_audience->id;
                    $campaign_attribute->name = $element;
                    $campaign_attribute->condition = $request->attribute_condition[$key] ?? null;
                    $campaign_attribute->value = $request->attribute_value[$key] ?? null;
                    $campaign_attribute->created_by = auth()->user()->id;

                    $campaign_attribute->save();
                }
            }
        }

        if (isset($request->retry_campaign) && !empty($request->retry_campaign) && isset($request->retry_hour) && !empty($request->retry_hour) && isset($request->retry_minute) && !empty($request->retry_minute)) 
        {
            foreach ($request->retry_hour as $key => $element) 
            {
                if (!empty($element)) 
                {
                    $campaign_retry = new CampaignRetry();

                    $campaign_retry->project_id = Helper::getProjectId();
                    $campaign_retry->campaign_id = $campaign->id;
                    $campaign_retry->hour = $element;
                    $campaign_retry->minute = $request->retry_minute[$key] ?? null;

                    $campaign_retry->save();
                }
            }
        }

        return response()->json(['status' => 1, 'redirect' => route('campaigns'), 'message' => 'Campaign successfully run.']);
    }

    public function campaignApi(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }
        
        if (request()->method() === 'GET')
        {
            return view('campaign.api');
        }

        $validations = [
            'campaign_name' => 'required',
            'template_id' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        if (isset($request->campaign_contact) && !empty($request->campaign_contact)) 
        {
            $contacts = Contact::where('project_id', Helper::getProjectId())->whereIn('id', $request->campaign_contact)->get();

        }
        else
        {
            $contacts = Contact::where('project_id', Helper::getProjectId())->get();
        }

        if ($contacts->isEmpty()) 
        {
            return response()->json(['status' => -1, 'message' => 'Contacts not found. Please add it first!.']);
        }

        $campaign = new Campaign();
        $campaign->project_id = Helper::getProjectId();
        $campaign->campaign = $request->campaign_name;
        $campaign->type = 'Broadcast API';
        $campaign->message_type = $request->template_id ? 0 : 1;
        $campaign->template_id = $request->template_id ?? null;
        $campaign->status = 'Live';
        $campaign->reference_id = Helper::getUUID('campaigns', 'reference_id'); 
        $campaign->created_by = auth()->user()->id;

        $campaign->save();

        if (!$contacts->isEmpty()) 
        {
            foreach ($contacts as $key => $contact) 
            {
                $campaign_contact = new CampaignContact();

                $campaign_contact->campaign_id = $campaign->id;
                $campaign_contact->contact_id = $contact->id;
                $campaign_contact->contact_number = $contact->whatsapp_number;

                $campaign_contact->save();
            }
        }

        $contacts = CampaignContact::where('campaign_id', $campaign->id)->count();

        $campaign->audience = !empty($contacts) ? $contacts : 0;
        $campaign->save();

        return response()->json(['status' => 1, 'redirect' => route('campaigns'), 'message' => 'Campaign successfully run.']);
    }

    public function campaignCsv(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }
        
        if (request()->method() === 'GET')
        {
            return view('campaign.broadcast-csv');
        }

        $validations = [
            'campaign_name' => 'required',
            'csv_file' => 'required|mimetypes:text/csv,text/plain,application/csv,application/vnd.ms-excel',
        ];

        if (Helper::$allow_regular_message === 1 && isset($request->message_type) && $request->message_type == 1) 
        {
            $validations['template_message'] = 'required';
        }
        else
        {
            $validations['template_id'] = 'required';
        }

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $path = $request->file('csv_file')->store('csv-files');

        $file_path = str_replace('public/', '', $path);

        $contacts = [];

        $row = 0;
        $phone_util = PhoneNumberUtil::getInstance();

        if (($handle = fopen(Storage::path($path), 'r')) !== false)
        {
            while (($data = fgetcsv($handle, 1000, ",")) !== false)
            {
                if ($row > 0)
                {
                    if (!empty($data[0]) && !empty($data[1])) 
                    {
                        $name = trim($data[0]);
                        $number = trim($data[1]);
                        $formatted_number = '';

                        try {
                            
                            $phone_number = $phone_util->parse($number, $request->default_country_flag);
                            if ($phone_util->isValidNumber($phone_number)) 
                            {
                                $formatted_number = $phone_util->format($phone_number, PhoneNumberFormat::E164);
                            }

                        } catch (NumberParseException $e) {
                            try {

                                $phone_number = $phone_util->parse($number, strtoupper($request->default_country_code));

                                if ($phone_util->isValidNumber($phone_number)) {
                                    $formatted_number = $phone_util->format($phone_number, PhoneNumberFormat::E164);
                                }

                            } catch (NumberParseException $ex) {
                                $formatted_number = '';
                            }
                        }

                        if (!empty($formatted_number)) 
                        {
                            $contacts[] = [
                                'name' => $name,
                                'whatsapp_number' => $formatted_number,
                            ];
                        }
                    }
                }

                $row++;
            }

            fclose($handle);
        } 
        else 
        {
            return response()->json(['status' => -1, 'message' => 'Unable to read the uploaded file.'], 500);
        }

        if (empty($contacts)) 
        {
            unlink(Storage::path($path));
            return response()->json(['status' => -1, 'message' => 'File is empty.'], 400);
        }

        if (!isset($request->schedule_date_and_time) || empty($request->schedule_date_and_time)) 
        {
            $campaign = Helper::runBroadcastCampaignFromMeta($request, $contacts);

            if (is_array($campaign))
            {
                return response()->json($campaign);
            }
        }

        $campaign = new Campaign();
        $campaign->project_id = Helper::getProjectId();
        $campaign->campaign = $request->campaign_name;
        $campaign->type = 'Broadcast CSV';
        $campaign->message_type = $request->message_type;
        $campaign->default_country_code = $request->default_country_code ?? null;
        $campaign->replace_tag = $request->replace_tag ?? 0;
        $campaign->template_id = $request->template_id ?? null; 
        $campaign->template_message = $request->template_message ?? null;
        $campaign->username = $request->test_campaign ?? null;
        $campaign->test_campaign = !empty($request->test_campaign) ? 1 : 0;
        $campaign->whatsapp_number = $request->whatsapp_number ?? null;
        $campaign->csv_file = $file_path;

        if (isset($request->schedule_date_and_time) && !empty($request->schedule_date_and_time)) 
        {
            $campaign->schedule_date_and_time = $request->schedule_date_and_time;
            $campaign->schedule_date = $request->schedule_date ?? null;
            $campaign->schedule_time = $request->schedule_time ?? null;
            $campaign->campaign_timezone = $request->campaign_timezone ?? null;
        } 

        $campaign->retry_campaign = $request->retry_campaign ?? 0;
        $campaign->status = 'Live';
        $campaign->reference_id = Helper::getUUID('campaigns', 'reference_id'); 
        $campaign->created_by = auth()->user()->id;

        $campaign->save();

        foreach ($contacts as $key => $contact) 
        {
            $campaign_contact = new CampaignContact();

            $campaign_contact->campaign_id = $campaign->id;
            $campaign_contact->contact_number = $contact['whatsapp_number'];

            $campaign_contact->save();
        }

        $contacts = CampaignContact::where('campaign_id', $campaign->id)->count();

        $campaign->audience = !empty($contacts) ? $contacts : 0;
        $campaign->save();

        if (isset($request->retry_campaign) && !empty($request->retry_campaign) && isset($request->retry_hour) && !empty($request->retry_hour) && isset($request->retry_minute) && !empty($request->retry_minute)) 
        {
            foreach ($request->retry_hour as $key => $element) 
            {
                if (!empty($element)) 
                {
                    $campaign_retry = new CampaignRetry();

                    $campaign_retry->project_id = Helper::getProjectId();
                    $campaign_retry->campaign_id = $campaign->id;
                    $campaign_retry->hour = $element;
                    $campaign_retry->minute = $request->retry_minute[$key] ?? null;

                    $campaign_retry->save();
                }
            }
        }

        return response()->json(['status' => 1, 'redirect' => route('campaigns'), 'message' => 'Campaign successfully run.']);
    }

    public function campaignRun(Request $request, $id = null)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back()->with('error_message', 'User not found!');
        }

        if (empty($id))
        {
            return redirect()->back()->with('error_message', 'Invalid Request');
        }

        $campaign = Campaign::where('id', $id)->where('project_id', Helper::getProjectId())->first();

        if (empty($campaign)) 
        {
            return redirect()->back()->with('error_message', 'Campaign not found!');
        }

        $campaign_audience = CampaignAudience::where('campaign_id', $id)->where('project_id', Helper::getProjectId())->get();

        if (!$campaign_audience->isEmpty()) 
        {
            foreach ($campaign_audience as $key => $audience) 
            {
                $campaign_audience_attributes = CampaignAudienceAttribute::where('project_id', Helper::getProjectId())->where('campaign_audience_id', $audience->id)->get();
            }
        }

        $campaign_contacts = CampaignContact::where('campaign_id', $id)->get();

        $campaign_response = Helper::runBroadcastCampaignFromMeta($campaign, $campaign_contacts);

        if (is_array($campaign_response))
        {
            return response()->json($campaign_response);
        }

        return redirect()->back()->with('message', 'Campaign successfully run.');
    }

    public function campaignAudience(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return response()->json(['status' => -1, 'message' => 'User not found!'], 500);
        }

        $contacts = 0;

        if (!empty($request->created_at_start_date) && !empty($request->created_at_end_date)) 
        {
            $contacts = Contact::where('project_id', Helper::getProjectId())->whereBetween('created_at', [\Carbon\Carbon::parse($request->created_at_start_date)->startOfDay(), \Carbon\Carbon::parse($request->created_at_end_date)->endOfDay()])->count();
        }
        else
        {
            return response()->json(['status' => -1, 'message' => 'Contacts not found!']);
        }

        if (empty($contacts) || $contacts === 0) 
        {
            return response()->json(['status' => -1, 'campaign_audience' => 0, 'message' => 'Contacts not found between ( '. $request->created_at_start_date .' , '.$request->created_at_end_date ]);
        }
        else
        {
            return response()->json(['status' => 1, 'campaign_audience' => $contacts, 'message' => $contacts .' contacts found between ( '. $request->created_at_start_date .' , '.$request->created_at_end_date ]);
        }
    }

    public function campaignTestRequest(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return response()->json(['status' => -1, 'message' => 'User not found!'], 500);
        }

        $validations = [
            'test_campaign' => 'required',
            'whatsapp_number' => 'required',
            'country_code' => 'required',
        ];

        if (Helper::$allow_regular_message === 1 && isset($request->message_type) && $request->message_type == 1) 
        {
            $validations['template_message'] = 'required';
        }
        else
        {
            $validations['template_id'] = 'required';
        }

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $contacts = [];

        $contacts[] = $request->country_code . $request->whatsapp_number; 

        $campaign = Helper::runBroadcastCampaignFromMeta($request, $contacts);

        if (is_array($campaign))
        {
            return response()->json($campaign);
        }

        if (!$campaign) 
        {
            return response()->json(['status' => -1, 'message' => 'Please Try Again!.']);
        }

        return response()->json(['status' => 1, 'message' => 'Campaign successfully run.']);
    }

    public function campaignCheckout(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return response()->json(['status' => -1, 'message' => 'User not found!'], 500);
        }

        $contacts = collect();
        $csv_campaign = false;

        if (isset($request->campaign_contact) && !empty($request->campaign_contact)) 
        {
            $contacts = Contact::where('project_id', Helper::getProjectId())->whereIn('id', explode(',', $request->campaign_contact))->get();
        }
        elseif (isset($request->campaign_tag) && !empty($request->campaign_tag)) 
        {
            $contacts = Contact::where('project_id', Helper::getProjectId())->whereIn('tag_id', explode(',', $request->campaign_tag))->get();

        } 
        elseif (isset($request->created_at_from) && !empty($request->created_at_from) && isset($request->created_at_to) && !empty($request->created_at_to)) 
        {
            $contacts = Contact::where('project_id', Helper::getProjectId())
                ->whereBetween('created_at', [\Carbon\Carbon::parse($request->created_at_from)->startOfDay(),\Carbon\Carbon::parse($request->created_at_to)->endOfDay()])->get();
        }
        elseif (isset($request->csv_file) && $request->hasFile('csv_file')) 
        {
            $path = $request->file('csv_file')->store('csv-files');

            $file_path = str_replace('public/', '', $path);

            $contacts = [];

            $row = 0;
            $csv_campaign = true;

            if (($handle = fopen(Storage::path($path), 'r')) !== false)
            {
                while (($data = fgetcsv($handle, 1000, ",")) !== false)
                {
                    if ($row > 0)
                    {
                        if (!empty($data[0]) && !empty($data[1])) 
                        {
                            $name = trim($data[0]);
                            $number = trim($data[1]);

                            if (!empty($number)) 
                            {
                                $contacts[] = [
                                    'whatsapp_number' => $number,
                                ];
                            }
                        }
                    }

                    $row++;
                }

                fclose($handle);
            } 
            else 
            {
                return response()->json(['status' => -1, 'message' => 'Unable to read the uploaded file.'], 500);
            }

            if (empty($contacts)) 
            {
                unlink(Storage::path($path));
                return response()->json(['status' => -1, 'message' => 'File is empty.'], 400);
            }
        } 
        else 
        {
            $contacts = Contact::where('project_id', Helper::getProjectId())->get();
        }

        $phone_util = PhoneNumberUtil::getInstance();
        $default_region = 'PK';
        $country_data = [];

        foreach ($contacts as $contact) 
        {
            $formatted_number = '';
            $region = $default_region;
            $whatsapp_number = is_array($contact) ? $contact['whatsapp_number'] : $contact->whatsapp_number;
 
            try {

                if (strpos($whatsapp_number, '+') !== 0) 
                {
                    $phone_number = $phone_util->parse($whatsapp_number, $default_region);
                }
                else
                {
                    $phone_number = $phone_util->parse($whatsapp_number, null);

                    $region = $phone_util->getRegionCodeForNumber($phone_number);
                }

                if (!$phone_util->isValidNumber($phone_number)) 
                {
                    foreach ($phone_util->getSupportedRegions() as $regionCode) 
                    {
                        try {

                            $phone_number = $phone_util->parse($whatsapp_number, $regionCode);
                            if ($phone_util->isValidNumberForRegion($phone_number, $regionCode)) 
                            {
                                $region = $regionCode;
                                break;
                            }
                        } catch (NumberParseException $e) {
                            continue;
                        }
                    }
                }

                if ($phone_util->isValidNumber($phone_number)) 
                {
                    $formatted_number = $phone_util->format($phone_number, PhoneNumberFormat::E164);
                }

                $dialing_code = '+' . $phone_number->getCountryCode();
                $country_name = Country::where('flag', strtoupper($region))->value('name') ?? 'Unknown';

            } catch (NumberParseException $e) {
                $formatted_number = '';
                $country_name = 'Unknown';
            }

            if (!empty($formatted_number)) 
            {
                if (isset($country_data[$region])) 
                {
                    $country_data[$region]['count']++;
                } 
                else 
                {
                    $country_data[$region] = [
                        'country_name' => $country_name,
                        'country_code' => $region,
                        'dialing_code' => $dialing_code ?? null,
                        'count' => 1,
                        'price' => 0,
                    ];
                }
            }
        }

        foreach ($country_data as &$data) 
        {
            $data['price'] = $data['count'] * ($csv_campaign ? Helper::$campaign_csv_broadcast_cost : Helper::$campaign_broadcast_cost);
        }

        return response()->json(['status' => 1, 'data' => $country_data]);
    }

    public function campaignRemove(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return response()->json(['status' => -1, 'message' => 'User not found!'], 500);
        }

        if (empty($request->id))
        {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        $ids = explode(',', $request->id);

        if (empty($ids))
        {
            return response()->json(['status' => -1, 'message' => 'Invalid Request'], 400);
        }

        $campaign_audience = CampaignAudience::whereIn('campaign_id', $ids)->where('project_id', Helper::getProjectId())->get();

        if (!$campaign_audience->isEmpty()) 
        {
            foreach ($campaign_audience as $key => $audience) 
            {
                CampaignAudienceAttribute::where('project_id', Helper::getProjectId())->where('campaign_audience_id', $audience->id)->delete();
            }
        }

        CampaignAudience::whereIn('campaign_id', $ids)->where('project_id', Helper::getProjectId())->delete();
        CampaignContact::whereIn('campaign_id', $ids)->delete();

        $data = Campaign::whereIn('id', $ids)->where('project_id', Helper::getProjectId())->delete();

        return response()->json(['status' => 1, 'message' => 'Done']);
    }
}
