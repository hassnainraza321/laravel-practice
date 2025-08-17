<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\UserAttribute;
use App\Models\ChatSetting;
use App\Models\ChatConfiguration;
use App\Models\ChatWorkingHour;
use App\Models\CannedMessage;
use DataTables;
use \Carbon\Carbon;
use DB;

class ManageController extends Controller
{
    public function optoutAdd(Request $request)
    {
        return view('manage.optin.view');
    }

    public function chatSettings(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }

        $chat_setting = ChatSetting::where('project_id', Helper::getProjectId())->first();

        if (empty($chat_setting)) 
        {
            $chat_setting = new ChatSetting();
            $chat_setting->project_id = Helper::getProjectId();
            $chat_setting->reference_id = Helper::getUUID('live_chat_settings', 'reference_id'); 
            $chat_setting->created_by = auth()->user()->id;
            $chat_setting->save();
        }

        $chat_working_hours = ChatWorkingHour::where('live_chat_setting_id', $chat_setting->id)->get();

        if ($chat_working_hours->isEmpty()) 
        {
            $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            $data = [];

            foreach ($days as $day) 
            {
                $data[] = [
                    'live_chat_setting_id' => $chat_setting->id,
                    'day' => $day,
                    'timezone' => 'Asia/Karachi',
                    'start_time' => '09:00',
                    'end_time' => '18:00',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            ChatWorkingHour::insert($data);
            $chat_working_hours = ChatWorkingHour::where('live_chat_setting_id', $chat_setting->id)->get();
        }

        $chat_configuration = ChatConfiguration::leftJoin('live_chat_settings', 'live_chat_settings.id', '=', 'live_chat_configurations.live_chat_setting_id')->where('live_chat_settings.project_id', Helper::getProjectId())->select('live_chat_configurations.*')->first();

        if (request()->method() === 'GET')
        {
            return view('manage.chat-settings.view', compact('chat_setting', 'chat_working_hours', 'chat_configuration'));
        }

        $chat_setting->auto_resolve_chat = $request->auto_resolve_chat ?? 0;
        $chat_setting->welcome_message = $request->welcome_message ?? 0;
        $chat_setting->off_hours_message = $request->off_hours_message ?? 0;
        $chat_setting->birthday_message = $request->birthday_message ?? 0;

        $chat_setting->save();

        return response()->json(['status' => 1, 'refresh' => true, 'message' => 'Settings updated successfully!']);
    }

    public function getChatConfiguration(Request $request, $type = null)
    {
        if (!$request->ajax())
        {
            return redirect()->back();
        }

        if (empty(Helper::getProjectId())) 
        {
            return response()->json(['status' => 1, 'redirect' => redirect()->route('projects')]);
        }

        $data = null;

        if (!empty($type)) 
        {
            $data = ChatConfiguration::leftJoin('live_chat_settings', 'live_chat_settings.id', '=', 'live_chat_configurations.live_chat_setting_id')->where('live_chat_settings.project_id', Helper::getProjectId())->where('live_chat_configurations.chat_type', $type)->select('live_chat_configurations.*')->first();
        }

        return response()->json(['status' => 1, 'modal' => view('manage.chat-settings.modals.config-message', ['data' => $data, 'type' => $type])->render()]);
    }

    public function chatConfiguration(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }
        
        $validations = [
            'template_type' => 'required',
        ];

        if ($request->template_type == 0) 
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

        $chat_configuration = ChatConfiguration::leftJoin('live_chat_settings', 'live_chat_settings.id', '=', 'live_chat_configurations.live_chat_setting_id')->where('live_chat_settings.project_id', Helper::getProjectId())->where('live_chat_configurations.chat_type', $request->chat_type)->select('live_chat_configurations.*')->first();

        if (empty($chat_configuration)) 
        {
            $chat_configuration = new ChatConfiguration();

            $chat_configuration->live_chat_setting_id = DB::table('live_chat_settings')->where('project_id', Helper::getProjectId())->value('id');
            $chat_configuration->chat_type = $request->chat_type; 
        }

        $chat_configuration->template_type = $request->template_type;
        $chat_configuration->template_id = $request->template_type == 1 ? $request->template_id : null;
        $chat_configuration->template_message = $request->template_type == 0 ? $request->template_message : null;
        $chat_configuration->sample_value = !empty($request->sample_value) ? json_encode($request->sample_value) : null;
        $chat_configuration->save();

        return response()->json(['status' => 1, 'redirect' => route('chat.settings'), 'message' => $chat_configuration->chat_type . ' configure successfully.']);
    }

    public function chatWorkingHours(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }

        //var_dump($request->all()); die();

        $chat_setting = ChatSetting::where('project_id', Helper::getProjectId())->first();

        if (empty($chat_setting)) 
        {
            return redirect()->back();
        }

        $chat_working_hours = ChatWorkingHour::where('live_chat_setting_id', $chat_setting->id)->get();

        if (!$chat_working_hours->isEmpty()) 
        {
            foreach ($chat_working_hours as $chat_working_hour) 
            {
                if ($chat_working_hour->day == 'Mon') 
                {
                    $chat_working_hour->start_time = $request->start_time_mon;
                    $chat_working_hour->end_time = $request->end_time_mon;
                    $chat_working_hour->status = $request->status_mon ?? 0;

                    $chat_working_hour->save();
                }

                if ($chat_working_hour->day == 'Tue') 
                {
                    $chat_working_hour->start_time = $request->start_time_tue;
                    $chat_working_hour->end_time = $request->end_time_tue;
                    $chat_working_hour->status = $request->status_tue ?? 0;

                    $chat_working_hour->save();
                }

                if ($chat_working_hour->day == 'Wed') 
                {
                    $chat_working_hour->start_time = $request->start_time_wed;
                    $chat_working_hour->end_time = $request->end_time_wed;
                    $chat_working_hour->status = $request->status_wed ?? 0;

                    $chat_working_hour->save();
                }

                if ($chat_working_hour->day == 'Thu') 
                {
                    $chat_working_hour->start_time = $request->start_time_thu;
                    $chat_working_hour->end_time = $request->end_time_thu;
                    $chat_working_hour->status = $request->status_thu ?? 0;

                    $chat_working_hour->save();
                }

                if ($chat_working_hour->day == 'Fri') 
                {
                    $chat_working_hour->start_time = $request->start_time_fri;
                    $chat_working_hour->end_time = $request->end_time_fri;
                    $chat_working_hour->status = $request->status_fri ?? 0;

                    $chat_working_hour->save();
                }

                if ($chat_working_hour->day == 'Sat') 
                {
                    $chat_working_hour->start_time = $request->start_time_sat;
                    $chat_working_hour->end_time = $request->end_time_sat;
                    $chat_working_hour->status = $request->status_sat ?? 0;

                    $chat_working_hour->save();
                }

                if ($chat_working_hour->day == 'Sun') 
                {
                    $chat_working_hour->start_time = $request->start_time_sun;
                    $chat_working_hour->end_time = $request->end_time_sun;
                    $chat_working_hour->status = $request->status_sun ?? 0;

                    $chat_working_hour->save();
                }

                $chat_working_hour->timezone = $request->timezone;

                $chat_working_hour->save();
            }
        }
        
        return response()->json(['status' => 1, 'redirect' => route('chat.settings'), 'message' => 'Working hours updated successfully.']);
    }

    public function userAttributes(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }
        
        if (request()->method() === 'GET')
        {
            return view('manage.user-attributes.view');
        }

        $validations = [
            'attribute_name' => 'required|array',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        foreach ($request->attribute_name as $key => $element) 
        {
            if (!empty($element)) 
            {
                $user_attribute = UserAttribute::where('project_id', Helper::getProjectId())->where('name', $element)->first();

                if (empty($user_attribute)) 
                {
                    $user_attribute = new UserAttribute();
                    $user_attribute->project_id = Helper::getProjectId();
                    $user_attribute->created_by = auth()->user()->id;
                    $user_attribute->reference_id = Helper::getUUID('user_attributes', 'reference_id');
                }

                $user_attribute->name = $element;
                $user_attribute->action = $request->attribute_action[$key] ?? null;
                
                $user_attribute->save();
            }
        }

        $existing_attributes = UserAttribute::where('project_id', Helper::getProjectId())->pluck('name')->toArray();

        $attributes_to_delete = array_diff($existing_attributes, $request->attribute_name);

        UserAttribute::where('project_id', Helper::getProjectId())->whereIn('name', $attributes_to_delete)->delete();
        
        return response()->json(['status' => 1, 'redirect' => route('user.attributes'), 'message' => 'Attributes added successfully!.']);
    }

    public function cannedMessage(Request $request)
    {
        $data = DB::table('canned_messages')
                ->leftJoin('users', 'users.id', '=', 'canned_messages.created_by')
                ->where('canned_messages.project_id', Helper::getProjectId())
                ->where('canned_messages.id', '!=', 0);
        
        if ($request->ajax())
        {
            $data = $data->select('canned_messages.*', 'users.first_name as user_name');

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
                    ->editColumn('text', function ($row) {

                        return $row->text ? $row->text : '';
                    })
                    ->addColumn('actions', function ($row) {

                        return '<div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-'. $row->id .'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-'. $row->id .'">
                                        <a class="dropdown-item" href="'. route('canned.message.add', $row->id) .'">Edit</a>
                                        <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="'. $row->id .'">Remove</a>
                                    </div>
                                </div>';
                        
                    })
                    ->rawColumns(['index_data', 'actions'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->whereRaw("canned_messages.name like ?", ["%{$keyword}%"]);
                    })
                    ->order(function ($query) {

                        if (!empty(request()->sort_by))
                        {
                            $sort_data = explode('-', request()->sort_by);

                            if (isset($sort_data[0]) && !empty($sort_data[0]) && isset($sort_data[1]) && !empty($sort_data[1]) && in_array(strtolower($sort_data[1]), ['asc', 'desc']) && in_array(strtolower($sort_data[0]), ['name', 'created_at']))
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

        return view('manage.canned-message.list')->with(compact('is_found'));
    }

    public function cannedMessageAdd(Request $request, $id = null)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }
        
        $data = null;

        if (!empty($id)) 
        {
            $data = CannedMessage::where('id', $id)->where('project_id', Helper::getProjectId())->first();
        }

        if (request()->method() === 'GET')
        {
            return view('manage.canned-message.add')->with(compact('data'));
        }

        $validations = [
            'name' => 'required',
        ];

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $data_found = true;

        if (empty($data))
        {
            $data = new CannedMessage();
            $data->project_id = Helper::getProjectId();
            $data->reference_id = Helper::getUUID('canned_messages', 'reference_id'); 
            $data->created_by = auth()->user()->id;
            $data_found = false;
        }

        $data->name = $request->name;
        $data->type = $request->message_type;
        $data->text = $request->text;
        $data->media_url = $request->media_url ?? null;
        $data->file_name = $request->file_name;

        if ($request->hasFile('media_file') && !empty($request->file('media_file')))
        {
            $file_path = $request->file('media_file')->store('images');

            $data->media_url = $file_path;
        }

        $data->save();

        if ($data_found) 
        {
            return response()->json(['status' => 1, 'redirect' => route('canned.message.add', $data->id), 'message' => 'Canned message updated successfully!.']);
        }

        return response()->json(['status' => 1, 'redirect' => route('canned.message.add', $data->id), 'message' => 'Canned message added successfully!.']);
    }

    public function cannedMessageRemove(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
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

        $data = CannedMessage::whereIn('id', $ids)->where('project_id', Helper::getProjectId())->delete();

        return response()->json(['status' => 1, 'message' => 'Done']);
    }
}
