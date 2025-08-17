<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Helper;

class WebhookController extends Controller
{
    public function webhook(Request $request)
    {
        Helper::createUpdateOption('webhook_request', $request->all());

        $body = file_get_contents('php://input');

        Helper::createUpdateOption('webhook_body', $body);

        return $request->hub_challenge;
    }
}
