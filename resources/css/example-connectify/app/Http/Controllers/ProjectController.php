<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Helpers\Helper;

class ProjectController extends Controller
{
    public function project(Request $request)
    { 
        if (request()->method() === 'GET')
        {
            $projects = Project::where('user_id', auth()->user()->id)->get();
            return view('projects')->with(compact('projects'));
        }

        $request->validate([
            'code' => 'required',
            'phone_number_id' => 'required',
            'whatsapp_business_account_id' => 'required',
        ]);

        $onboarding = Project::where('phone_number_id', $request->phone_number_id)->first();

        if (empty($onboarding))
        {
            $onboarding = new Project();
        }

        $onboarding->user_id = auth()->user()->id;
        $onboarding->code = $request->code;
        $onboarding->phone_number_id = $request->phone_number_id;
        $onboarding->whatsapp_business_account_id = $request->whatsapp_business_account_id; 
        $onboarding->reference_id = Helper::getUUID('projects', 'reference_id'); 
        $onboarding->created_by = auth()->user()->id;
        $onboarding->save();

        $access_token = Helper::getAccessTokenFromMeta($request->code);

        if (empty($access_token)) 
        {
            return response()->json(['success' => false]);
        }

        $onboarding->access_token = $access_token;
        $onboarding->save();

        $whatsapp_accounts = Helper::getAccountDetailsFromMeta($onboarding->access_token, $onboarding->whatsapp_business_account_id);

        if (!empty($whatsapp_accounts))
        {
            foreach ($whatsapp_accounts as $key => $whatsapp_account)
            {
                $account = Project::where('phone_number_id', $whatsapp_account['id'])->first();

                if (!empty($account))
                {
                    $account->business_name = $whatsapp_account['verified_name'];
                    $account->phone_number = $whatsapp_account['display_phone_number'];
                    $account->save();
                }
            }
        }

        $account_review_status = Helper::accountReviewStatusFromMeta($onboarding->access_token, $onboarding->whatsapp_business_account_id);

        $onboarding->account_review_status = strtoupper($account_review_status);
        $onboarding->status = !empty($account_review_status) && strtoupper($account_review_status) == 'APPROVED' ? 1 : 0;
        $onboarding->save();

        $subscribe_to_webhook = Helper::subscribeToWebhookOnMeta($onboarding->access_token, $onboarding->whatsapp_business_account_id);

        return response()->json(['success' => true]);
    }
}
