<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TemplateCallToAction;
use App\Models\Template;
use App\Models\Project;
use App\Helpers\Helper;
use DB;
use Str;
use Excel;
use DataTables;
use \Carbon\Carbon;

class TemplateController extends Controller
{
    public function list(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }
        
        $data = DB::table('templates')
                    ->leftJoin('template_categories', 'template_categories.id', '=', 'templates.category_id')
                    ->leftJoin('template_types', 'template_types.id', '=', 'templates.type_id')
                    ->where('templates.project_id', Helper::getProjectId());
        
        if ($request->ajax())
        {
            $data = $data->select('templates.*', 'template_categories.name as category_name', 'template_types.name as type_name');

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
                    ->editColumn('health', function ($row) {

                        if ($row->health == 'Low') 
                        {
                            $health = '<span class="badge text-bg-danger">'. $row->health .'</span>';
                        }
                        else if ($row->health == 'High')
                        {
                            $health = '<span class="badge text-bg-success">'. $row->health .'</span>';
                        }
                        else if ($row->health == 'Medium')
                        {
                            $health = '<span class="badge text-bg-warning">'. $row->health .'</span>';
                        }

                        return $health ?? '-';
                    })
                    ->addColumn('row_url', function ($row) {

                        return route('template.create', $row->id);
                        
                    })
                    ->editColumn('status', function ($row) {

                        if ($row->status == 'PENDING') 
                        {
                            $status = '<span class="badge text-bg-secondary">'. $row->status .'</span>';
                        }
                        else if ($row->status == 'APPROVED')
                        {
                            $status = '<span class="badge text-bg-success">'. $row->status .'</span>';
                        }
                        else if ($row->status == 'REJECTED')
                        {
                            $status = '<span class="badge text-bg-danger">'. $row->status .'</span>';
                        }

                        return $status ?? '-';
                         
                    })
                    ->addColumn('actions', function ($row) {

                        return '<div class="dropdown">
                                    <button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton-'. $row->id .'" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton-'. $row->id .'">
                                        <a class="dropdown-item" href="'. route('template.create', $row->id) .'">Edit</a>
                                        <a class="dropdown-item remove-item-button" href="javascript:void(0)" data-id="'. $row->id .'">Remove</a>
                                    </div>
                                </div>';
                        
                    })
                    ->rawColumns(['index_data', 'status', 'health', 'actions'])
                    ->filterColumn('filter_index', function($query, $keyword) {
                        $query->whereRaw("templates.name like ?", ["%{$keyword}%"]);
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

        return view('manage.template.list')->with(compact('is_found'));
    }

    public function create(Request $request, $id = null)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }
        
        $data = null;

        if (!empty($id)) 
        {
            if (Str::isUuid($id)) 
            {
                $data = Template::where('reference_id', $id)->first();

                if (!empty($data)) 
                {
                    $approved_template = DB::table('pre_approved_templates')->where('template_id', $data->id)->first();

                    if (empty($approved_template)) 
                    {
                        return redirect()->route('template.message');
                    }
                }
                else
                {
                    return redirect()->route('template.message');
                }
            } 
            elseif (is_numeric($id)) 
            {
                $data = Template::where('id', $id)->where('project_id', Helper::getProjectId())->first();
            }
            else
            {
                return redirect()->route('template.message');
            }
        }

        if (request()->method() === 'GET')
        {
            if (!empty($id) && empty($data)) 
            {
                return redirect()->route('template.message');
            }

            if ($request->ajax()) 
            {
                return response()->json(['status' => 1, 'data' => $data]);
            }

            return view('manage.template.add')->with(compact('data'));
        }

        $validations = [
            'name' => 'required',
            'category_id' => 'required',
            'type_id' => 'required',
            'template_language_id' => 'required',
        ];

        if ($request->type_id == 2 || $request->type_id == 4) 
        {
            $validations['template_media'] = 'required';
        }

        $validator = \Validator::make($request->all(), $validations);

        if ($validator->fails())
        {
            return response()->json(['status' => -1, 'message' => $validator->messages()->toArray()]);
        }

        $project = DB::table('projects')->where('id', Helper::getProjectId())->where('is_active', 1)->first();

        if (empty($project)) 
        {
            return response()->json(['status' => -1, 'message' => 'Invalid Request!']);
        }

        $success_message = null;

        if (!empty($request->category_id) && $request->category_id == 3) 
        {
            $authentication_template = Helper::authenticationTemplateFromMeta($request, $project, $data);

            if (!empty($authentication_template)) 
            {
                $data_found = true;

                if (empty($data))
                {
                    $data = new Template();
                    $data->project_id = Helper::getProjectId();
                    $data->reference_id = Helper::getUUID('templates', 'reference_id'); 
                    $data->created_by = auth()->user()->id;
                    $data_found = false;
                }

                $data->meta_template_id = $authentication_template['id'];
                $data->category_id = $request->category_id;
                $data->name = str_replace(' ', '_', strtolower($request->name));
                $data->type_id = $request->type_id;
                $data->sample_value = json_encode($request->sample_value);
                $data->expiration_warning = $request->expiration_warning;
                $data->security_disclaimer = isset($request->security_disclaimer) && !empty($request->security_disclaimer) ? $request->security_disclaimer : 0;
                $data->template_language_id = $request->template_language_id;
                $data->status = $authentication_template['status'] ?? 'PENDING';

                if (!empty($authentication_template['status'])) 
                {
                    if ($authentication_template['status'] == 'PENDING') 
                    {
                        $data->health = 'Medium';
                    }
                    else if ($authentication_template['status'] == 'APPROVED')
                    {
                        $data->health = 'High';
                    }
                    else if ($authentication_template['status'] == 'REJECTED')
                    {
                        $data->health = 'Low';
                    }
                }

                $data->save();

                $success_message = 'Authentication template ' . ($data_found ? 'updated' : 'created') . ' successfully!';
            }
            else
            {
                return response()->json(['status' => -1, 'message' => 'Please Refresh page and try again!']);
            }
        }

        if (!empty($request->category_id) && $request->category_id == 2 && empty($request->type_id) && empty($request->quick_reply) && empty($request->type) && empty($request->button_title) && empty($request->button_value)) 
        {
            $marketing_template = Helper::marketingTemplateFromMeta($request, $project, $data);

            if (!empty($marketing_template)) 
            {
                $data_found = true;

                if (empty($data))
                {
                    $data = new Template();
                    $data->project_id = Helper::getProjectId();
                    $data->reference_id = Helper::getUUID('templates', 'reference_id'); 
                    $data->created_by = auth()->user()->id;
                    $data_found = false;
                }

                $data->meta_template_id = $marketing_template['id'];
                $data->category_id = $request->category_id;
                $data->name = str_replace(' ', '_', strtolower($request->name));
                $data->type_id = $request->type_id;
                $data->header_text = $request->header_text;
                $data->content = $request->content;
                $data->footer_text = $request->footer_text;
                $data->sample_value = json_encode($request->sample_value);
                $data->template_language_id = $request->template_language_id;
                $data->status = $marketing_template['status'];

                if (!empty($marketing_template['status'])) 
                {
                    if ($marketing_template['status'] == 'PENDING') 
                    {
                        $data->health = 'Medium';
                    }
                    else if ($marketing_template['status'] == 'APPROVED')
                    {
                        $data->health = 'High';
                    }
                    else if ($marketing_template['status'] == 'REJECTED')
                    {
                        $data->health = 'Low';
                    }
                }

                $data->save();

                $success_message = 'Marketing template ' . ($data_found ? 'updated' : 'created') . ' successfully';
            }
            else
            {
                return response()->json(['status' => -1, 'message' => 'Please Refresh page and try again!']);
            }
        }

        if (!empty($request->category_id) && $request->category_id == 2 && !empty($request->type_id) && $request->type_id == 1) 
        {
            if (empty($request->quick_reply)) 
            {
                return response()->json(['status' => -1, 'message' => 'At least one Quick Reply is required!']);
            }

            $marketing_text_template = Helper::marketingTextTemplateFromMeta($request, $project, $data);

            if (!empty($marketing_template)) 
            {
                $data_found = true;

                if (empty($data))
                {
                    $data = new Template();
                    $data->project_id = Helper::getProjectId();
                    $data->reference_id = Helper::getUUID('templates', 'reference_id'); 
                    $data->created_by = auth()->user()->id;
                    $data_found = false;
                }

                $data->meta_template_id = $marketing_text_template['id'];
                $data->category_id = $request->category_id;
                $data->name = str_replace(' ', '_', strtolower($request->name));
                $data->type_id = $request->type_id;
                $data->header_text = $request->header_text;
                $data->content = $request->content;
                $data->footer_text = $request->footer_text;
                $data->sample_value = json_encode($request->sample_value);
                $data->template_language_id = $request->template_language_id;
                $data->status = $marketing_text_template['status'];

                if (!empty($marketing_text_template['status'])) 
                {
                    if ($marketing_text_template['status'] == 'PENDING') 
                    {
                        $data->health = 'Medium';
                    }
                    else if ($marketing_text_template['status'] == 'APPROVED')
                    {
                        $data->health = 'High';
                    }
                    else if ($marketing_text_template['status'] == 'REJECTED')
                    {
                        $data->health = 'Low';
                    }
                }

                $data->save();

                $success_message = 'Marketing text template ' . ($data_found ? 'updated' : 'created') . ' successfully';
            }
            else
            {
                return response()->json(['status' => -1, 'message' => 'Please Refresh page and try again!']);
            }
        }

        if (!empty($request->category_id) && $request->category_id == 2 && !empty($request->type_id) && $request->type_id == 2) 
        {
            if (empty($request->type) && empty($request->button_title) && empty($request->button_value)) 
            {
                return response()->json(['status' => -1, 'message' => 'Phone Number and URL call to action is required!']);
            }

            if ($request->hasFile('template_media')) 
            {
                if (!empty($data->template_media)) {
                    Storage::disk('public')->delete($data->template_media);
                }

                $uploadedFile = $request->file('template_media'); 
                $media_path = $uploadedFile->store('template-media', 'public'); 
                $media = 'storage/' . $media_path; 

                $mimeType = $uploadedFile->getMimeType();

                $meta_media_link = Helper::templateMediaFromMeta($project, public_path($media), $mimeType);

                var_dump($meta_media_link); die();

                if ($meta_media_link) 
                {
                    return response()->json(['status' => -1, 'message' => 'Template media not correct!']);
                }
            }


            $marketing_image_template = Helper::marketingImageTemplateFromMeta($request, $project, $data);

            if (!empty($marketing_image_template)) 
            {
                $data_found = true;

                if (empty($data))
                {
                    $data = new Template();
                    $data->project_id = Helper::getProjectId();
                    $data->reference_id = Helper::getUUID('templates', 'reference_id'); 
                    $data->created_by = auth()->user()->id;
                    $data_found = false;
                }

                $data->meta_template_id = $marketing_image_template['id'];
                $data->category_id = $request->category_id;
                $data->name = str_replace(' ', '_', strtolower($request->name));
                $data->type_id = $request->type_id;
                $data->content = $request->content;
                $data->footer_text = $request->footer_text;
                $template_media->image = $media ?? null;
                $data->sample_value = json_encode($request->sample_value);
                $data->template_language_id = $request->template_language_id;
                $data->status = $marketing_image_template['status'];

                if (!empty($marketing_image_template['status'])) 
                {
                    if ($marketing_image_template['status'] == 'PENDING') 
                    {
                        $data->health = 'Medium';
                    }
                    else if ($marketing_image_template['status'] == 'APPROVED')
                    {
                        $data->health = 'High';
                    }
                    else if ($marketing_image_template['status'] == 'REJECTED')
                    {
                        $data->health = 'Low';
                    }
                }

                $data->save();

                $success_message = 'Marketing image template ' . ($data_found ? 'updated' : 'created') . ' successfully';
            }
            else
            {
                return response()->json(['status' => -1, 'message' => 'Please Refresh page and try again!']);
            }
        }

        if (!empty($request->category_id) && $request->category_id == 1 && !empty($request->type_id) && $request->type_id == 5) 
        {
            if (empty($request->quick_reply)) 
            {
                return response()->json(['status' => -1, 'message' => 'At least one Quick Reply is required!']);
            }

            $utility_location_template = Helper::utilityLocationTemplateFromMeta($request, $project, $data);

            if (!empty($marketing_template)) 
            {
                $data_found = true;

                if (empty($data))
                {
                    $data = new Template();
                    $data->project_id = Helper::getProjectId();
                    $data->reference_id = Helper::getUUID('templates', 'reference_id'); 
                    $data->created_by = auth()->user()->id;
                    $data_found = false;
                }

                $data->meta_template_id = $utility_location_template['id'];
                $data->category_id = $request->category_id;
                $data->name = str_replace(' ', '_', strtolower($request->name));
                $data->type_id = $request->type_id;
                $data->content = $request->content;
                $data->footer_text = $request->footer_text;
                $data->sample_value = json_encode($request->sample_value);
                $data->template_language_id = $request->template_language_id;
                $data->status = $utility_location_template['status'];

                if (!empty($utility_location_template['status'])) 
                {
                    if ($utility_location_template['status'] == 'PENDING') 
                    {
                        $data->health = 'Medium';
                    }
                    else if ($utility_location_template['status'] == 'APPROVED')
                    {
                        $data->health = 'High';
                    }
                    else if ($utility_location_template['status'] == 'REJECTED')
                    {
                        $data->health = 'Low';
                    }
                }

                $data->save();

                $success_message = 'Utility location template ' . ($data_found ? 'updated' : 'created') . ' successfully';
            }
            else
            {
                return response()->json(['status' => -1, 'message' => 'Please Refresh page and try again!']);
            }
        }

        if (!empty($request->category_id) && $request->category_id == 1 && !empty($request->type_id) && $request->type_id == 4) 
        {
            if (empty($request->type) || empty($request->button_title) || empty($request->button_value)) 
            {
                return response()->json(['status' => -1, 'message' => 'Phone Number and URL call to action is required!']);
            }

            $marketing_image_template = Helper::utilityDocumentTemplateFromMeta($request, $project, $data);

            if (!empty($marketing_image_template)) 
            {
                $data_found = true;

                if (empty($data))
                {
                    $data = new Template();
                    $data->project_id = Helper::getProjectId();
                    $data->reference_id = Helper::getUUID('templates', 'reference_id'); 
                    $data->created_by = auth()->user()->id;
                    $data_found = false;
                }

                $data->meta_template_id = $marketing_image_template['id'];
                $data->category_id = $request->category_id;
                $data->name = str_replace(' ', '_', strtolower($request->name));
                $data->type_id = $request->type_id;
                $data->content = $request->content;
                $data->footer_text = $request->footer_text;
                $data->sample_value = json_encode($request->sample_value);
                $data->template_language_id = $request->template_language_id;
                $data->status = $marketing_image_template['status'];

                if (!empty($marketing_image_template['status'])) 
                {
                    if ($marketing_image_template['status'] == 'PENDING') 
                    {
                        $data->health = 'Medium';
                    }
                    else if ($marketing_image_template['status'] == 'APPROVED')
                    {
                        $data->health = 'High';
                    }
                    else if ($marketing_image_template['status'] == 'REJECTED')
                    {
                        $data->health = 'Low';
                    }
                }

                $data->save();

                $success_message = 'Utility document template ' . ($data_found ? 'updated' : 'created') . ' successfully';
            }
            else
            {
                return response()->json(['status' => -1, 'message' => 'Please Refresh page and try again!']);
            }
        }

        $call_to_action = TemplateCallToAction::where('template_id', $data->id)->delete();

        if (isset($request->type) && !empty($request->type) && isset($request->button_title) && !empty($request->button_title) || isset($request->button_value) && !empty($request->button_value)) 
        {
            foreach ($request->button_title as $key => $btn_title) 
            {
                $action = new TemplateCallToAction();

                $action->template_id = $data->id;
                $action->reference_id = Helper::getUUID('template_call_to_actions', 'reference_id'); 
                $action->created_by = auth()->user()->id;
                $action->type = $request->type[$key];
                $action->button_title = $btn_title;
                $action->button_value = $request->button_value[$key];

                $action->save();
            }
        }

        if (isset($request->quick_reply) && !empty($request->quick_reply)) 
        {
            foreach ($request->quick_reply as $key => $quick_reply_title) 
            {
                $action = new TemplateCallToAction();

                $action->template_id = $data->id;
                $action->reference_id = Helper::getUUID('template_call_to_actions', 'reference_id'); 
                $action->created_by = auth()->user()->id;
                $action->type = 'Quick Reply';
                $action->button_title = $quick_reply_title;

                $action->save();

            }
        }

        if (isset($request->coupon_code) && !empty($request->coupon_code)) 
        {
            foreach ($request->coupon_code as $key => $code) 
            {
                $action = new TemplateCallToAction();

                $action->template_id = $data->id;
                $action->reference_id = Helper::getUUID('template_call_to_actions', 'reference_id'); 
                $action->created_by = auth()->user()->id;
                $action->type = 'Coupon Code';
                $action->button_title = $code;

                $action->save();
            }
        }

        if ($data_found) 
        {
            return response()->json(['status' => 1, 'message' => $success_message ?? 'Templete Updated Successfully']);
        }

        return response()->json(['status' => 1, 'redirect' => route('template.create', $data->id), 'message' => $success_message ?? 'Templete Created Successfully']);
    }

    public function remove(Request $request)
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

        $templates = Template::where('project_id', Helper::getProjectId())->whereIn('id', $ids)->get();
        $project = Project::where('id', Helper::getProjectId())->where('status', 1)->first();

        if ($templates->isEmpty()) 
        {
            return response()->json(['status' => -1, 'message' => 'No templates found'], 400);
        }

        $remaining_templates = [];

        foreach ($templates as $template) 
        {
            $response = Helper::deleteTemplateFromMeta($project, $template);

            if ($response) 
            {
                Template::where('id', $template->id)->delete();
            } 
            else
            {
                $remaining_templates[] = $template->id;
            }
        }

        if (empty($remaining_templates)) 
        {
            return response()->json(['status' => 1, 'message' => 'All templates deleted successfully']);
        }

        return response()->json([
            'status' => -1, 
            'message' => 'Some templates could not be deleted. Please try again.', 
            'failed_ids' => $remaining_templates
        ]);
    }

    public function preApprovedTemplate(Request $request)
    {
        if (empty(Helper::getProjectId())) 
        {
            return redirect()->back();
        }
        
        if (request()->method() === 'GET')
        {
            $templates = DB::table('pre_approved_templates')
                    ->leftJoin('templates', 'templates.id', '=', 'pre_approved_templates.template_id')
                    ->leftJoin('template_categories', 'template_categories.id', '=', 'templates.category_id')
                    ->leftJoin('template_types', 'template_types.id', '=', 'templates.type_id')
                    ->where('status', 'APPROVED')
                    ->select('templates.*', 'template_categories.name as category_name', 'template_types.name as type_name')
                    ->get();

            return view('manage.template.pre-approved', compact('templates'));
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

        foreach ($ids as $key => $template_id) 
        {
            $template = DB::table('pre_approved_templates')->where('template_id', $template_id)->first();

            if (empty($template)) 
            {
                DB::table('pre_approved_templates')->insert([

                    'user_id' => auth()->user()->id,
                    'template_id' => $template_id,

                ]);
            }
        }

        return response()->json(['status' => 1, 'message' => 'Done']);
    }
}
