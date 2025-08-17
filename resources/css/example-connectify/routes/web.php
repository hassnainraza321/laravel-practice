<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ManageController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\ProjectVerification;
use App\Http\Middleware\User;

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::any('/login', [AuthController::class, 'login'])->name('login');
Route::any('/register', [AuthController::class, 'register'])->name('register');
Route::get('auth/{provider}', [AuthController::class, 'redirect'])->name('social.redirect');
Route::get('{provider}/callback', [AuthController::class, 'callback'])->name('social.callback');

Route::middleware([User::class])->group(function () 
{
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/project', [ProjectController::class, 'project'])->name('project');
    Route::any('/profile', [ProfileController::class, 'index'])->name('profile');

    Route::middleware([ProjectVerification::class])->group(function ()
    {
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

        Route::get('/contacts', [ContactController::class, 'list'])->name('contacts');
        Route::post('/contacts/import', [ContactController::class, 'import'])->name('contacts.import');
        Route::get('/contacts/export', [ContactController::class, 'export'])->name('contacts.export');
        Route::get('/contacts/get/{id?}', [ContactController::class, 'get'])->name('contacts.get');
        Route::any('/contacts/add/{id?}', [ContactController::class, 'add'])->name('contacts.add');
        Route::post('/contacts/remove', [ContactController::class, 'remove'])->name('contacts.remove');

        Route::get('/manage/template-message', [TemplateController::class, 'list'])->name('template.message');
        Route::any('/manage/template-message/create/{id?}', [TemplateController::class, 'create'])->name('template.create');
        Route::post('/manage/template-message/remove', [TemplateController::class, 'remove'])->name('template.remove');
        Route::any('/manage/pre-approved-template', [TemplateController::class, 'preApprovedTemplate'])->name('pre.approved.template');

        Route::get('/manage/tags', [TagController::class, 'list'])->name('tags');
        Route::post('/manage/tags/import', [TagController::class, 'import'])->name('tags.import');
        Route::get('/manage/tags/export', [TagController::class, 'export'])->name('tags.export');
        Route::get('/manage/tags/get/{id?}', [TagController::class, 'get'])->name('tags.get');
        Route::any('/manage/tags/add/{id?}', [TagController::class, 'add'])->name('tags.add');
        Route::post('/manage/tags/remove', [TagController::class, 'remove'])->name('tags.remove');

        Route::get('/campaigns', [ CampaignController::class, 'campaignList'])->name('campaigns');
        Route::any('/campaigns/create/broadcast', [CampaignController::class, 'campaignAdd'])->name('campaigns.add');
        Route::any('/campaigns/run/broadcast/{id?}', [CampaignController::class, 'campaignRun'])->name('campaigns.run');
        Route::post('/campaigns/broadcast/remove', [CampaignController::class, 'campaignRemove'])->name('campaigns.remove');
        Route::any('/campaigns/create/api', [CampaignController::class, 'campaignApi'])->name('campaigns.api');
        Route::any('/campaigns/create/broadcast-csv', [CampaignController::class, 'campaignCsv'])->name('campaigns.csv');
        Route::post('/campaigns/check/audience', [CampaignController::class, 'campaignAudience'])->name('campaigns.audience');
        Route::post('/campaigns/test/request', [CampaignController::class, 'campaignTestRequest'])->name('campaigns.test.request');
        Route::post('/campaigns/checkout', [CampaignController::class, 'campaignCheckout'])->name('campaigns.checkout');

        Route::get('/manage/optout', [ManageController::class, 'optoutAdd'])->name('optout');

        Route::any('/manage/chat-settings', [ManageController::class, 'chatSettings'])->name('chat.settings');
        Route::get('/manage/chat-configuration/get/{type?}', [ManageController::class, 'getChatConfiguration'])->name('get.chat.configuration');
        Route::post('/manage/chat-configuration', [ManageController::class, 'chatConfiguration'])->name('chat.configuration');
        Route::post('/manage/chat-working-hours', [ManageController::class, 'chatWorkingHours'])->name('chat.working.hours');

        Route::any('/manage/user-attributes', [ManageController::class, 'userAttributes'])->name('user.attributes');

        Route::get('/manage/canned-message', [ManageController::class, 'cannedMessage'])->name('canned.message');
        Route::any('/manage/canned-message/add/{id?}', [ManageController::class, 'cannedMessageAdd'])->name('canned.message.add');
        Route::post('/manage/canned-message/remove', [ManageController::class, 'cannedMessageRemove'])->name('canned.message.remove');
    });    
});

Route::any('/webhook', [WebhookController::class, 'webhook'])->name('webhook');