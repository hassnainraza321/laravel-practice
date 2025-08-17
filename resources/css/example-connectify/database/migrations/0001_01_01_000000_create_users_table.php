<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_code', 7)->nullable();
            $table->string('flag', 7)->nullable();
            $table->string('languages')->nullable();
            $table->string('language_code')->nullable();
            $table->string('country_code', 7)->nullable();
            $table->string('country_iso_code_3', 3)->nullable();
            $table->string('country_iso_code_2', 2)->nullable();
            $table->string('currency_name')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('reference_id', 36)->unique();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('display_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->boolean('role')->default(0); // 1 for top admin, 0 for user, 2 for staff
            $table->boolean('is_active')->default(1);
            $table->string('image')->nullable();
            $table->string('reference_id', 36)->unique();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->string('business_name')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('code')->nullable();
            $table->text('access_token')->nullable();
            $table->string('phone_number_id', 100)->nullable();
            $table->string('whatsapp_business_account_id', 100)->nullable();
            $table->string('account_review_status', 8)->nullable();
            $table->boolean('status')->default(0);
            $table->string('reference_id', 36)->unique();
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });

        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('option_key', 75)->index();
            $table->text('option_value')->nullable();
            $table->timestamps();
        });

        Schema::create('tag_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->string('name');
            $table->string('reference_id', 36)->unique();
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->string('title');
            $table->boolean('customer_journey')->default(0);
            $table->boolean('first_message')->default(0);
            $table->foreignId('tag_category_id')->nullable();
            $table->timestamps();
        });

        Schema::create('tag_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->string('first_message');
            $table->foreignId('tag_id')->nullable();
            $table->string('reference_id', 36)->unique();
            $table->timestamps();
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->string('name');
            $table->string('whatsapp_number');
            $table->string('source')->nullable();
            $table->foreignId('tag_id')->nullable();
            $table->string('reference_id', 36)->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('template_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('reference_id', 36)->unique();
            $table->timestamps();
        });

        Schema::create('template_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('reference_id', 36)->unique();
            $table->timestamps();
        });

        Schema::create('template_languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('reference_id', 36)->unique();
            $table->timestamps();
        });

        Schema::create('templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->string('name');
            $table->foreignId('category_id')->nullable()->index();
            $table->foreignId('type_id')->nullable()->index();
            $table->string('meta_template_id')->nullable();
            $table->string('header_text')->nullable();
            $table->text('content')->nullable();
            $table->string('footer_text')->nullable();
            $table->string('template_media')->nullable();
            $table->string('carousel_media_type')->nullable();
            $table->text('card_body_text')->nullable();
            $table->string('sample_value')->nullable();
            $table->integer('expiration_warning')->nullable();
            $table->boolean('security_disclaimer')->default(0);
            $table->string('limited_time_offer')->nullable();
            $table->boolean('offer_expires')->default(0);
            $table->foreignId('template_language_id')->nullable()->index();
            $table->string('status')->nullable();
            $table->string('health')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('reference_id', 36)->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('template_call_to_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->index();
            $table->string('type');
            $table->string('button_title');
            $table->string('button_value')->nullable();
            $table->string('reference_id', 36)->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->string('campaign');
            $table->string('type')->nullable(); // broadcast, api, broadcast csv, meta ads 
            $table->boolean('message_type')->default(0); // 0 pre approved template 1 for regular message
            $table->foreignId('template_id')->nullable()->index();
            $table->text('template_message')->nullable();
            $table->string('username')->nullable();
            $table->boolean('test_campaign')->default(0);
            $table->string('whatsapp_number')->nullable();
            $table->boolean('enable_capi')->default(0);
            $table->string('audience')->nullable();
            $table->string('csv_file', 80)->nullable();
            $table->string('attribute_option')->nullable();
            $table->string('default_country_code')->nullable();
            $table->boolean('replace_tag')->default(0);
            $table->boolean('schedule_date_and_time')->default(0);
            $table->date('schedule_date')->nullable();
            $table->time('schedule_time')->nullable();
            $table->string('campaign_timezone')->nullable();
            $table->boolean('retry_campaign')->default(0);
            $table->string('status')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('reference_id', 36)->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('campaign_audience', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->foreignId('campaign_id')->index();
            $table->boolean('last_seen')->nullable(); // 0 In 24hr 1 This week 2 This month
            $table->date('last_seen_start_date')->nullable();
            $table->date('last_seen_end_date')->nullable();
            $table->boolean('created')->nullable(); // 0 Today 1 This week 2 This month
            $table->date('created_at_start_date')->nullable();
            $table->date('created_at_end_date')->nullable();
            $table->boolean('optin')->default(1); // 0 No 1 Yes 2 All
            $table->boolean('incoming_blocked')->default(0); // 0 No 1 Yes 2 All
            $table->boolean('read_status')->default(0); // 0 Read 1 Unread 2 All
            $table->string('reference_id', 36)->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('campaign_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->index();
            $table->foreignId('contact_id')->nullable()->index();
            $table->string('contact_number');
            $table->timestamps();
        });

        Schema::create('campaign_audience_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->foreignId('campaign_audience_id')->index();
            $table->string('name');
            $table->string('condition');
            $table->string('value');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('campaign_retries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->index();
            $table->foreignId('project_id')->index();
            $table->unsignedInteger('hour');
            $table->unsignedInteger('minute');
        });

        Schema::create('pre_approved_templates', function (Blueprint $table) {
            $table->foreignId('user_id')->index();
            $table->foreignId('template_id')->index();
            $table->primary(['user_id', 'template_id']);
        });

        Schema::create('user_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->string('name');
            $table->string('action')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('reference_id', 36)->unique();
            $table->timestamps();
        });

        Schema::create('live_chat_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->boolean('auto_resolve_chat')->default(0);
            $table->boolean('welcome_message')->default(0);
            $table->boolean('off_hours_message')->default(0);
            $table->boolean('birthday_message')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('reference_id', 36)->unique();
            $table->timestamps();
        });

        Schema::create('live_chat_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_chat_setting_id')->index();
            $table->string('chat_type');
            $table->boolean('template_type')->default(0); // 0 for regular message 1 pre approved template
            $table->foreignId('template_id')->nullable()->index();
            $table->text('template_message')->nullable();
            $table->text('sample_value')->nullable();
            $table->timestamps();
        });

        Schema::create('live_chat_working_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('live_chat_setting_id')->index();
            $table->string('day');
            $table->string('timezone');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });

        Schema::create('canned_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->index();
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('media_url')->nullable();
            $table->text('text')->nullable();
            $table->string('file_name')->nullable();
            $table->boolean('favourite')->default(0);
            $table->boolean('is_active')->default(1);
            $table->string('reference_id', 36)->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });

        // Schema::create('password_reset_tokens', function (Blueprint $table) {
        //     $table->string('email')->primary();
        //     $table->string('token');
        //     $table->timestamp('created_at')->nullable();
        // });

        // Schema::create('sessions', function (Blueprint $table) {
        //     $table->string('id')->primary();
        //     $table->foreignId('user_id')->nullable()->index();
        //     $table->string('ip_address', 45)->nullable();
        //     $table->text('user_agent')->nullable();
        //     $table->longText('payload');
        //     $table->integer('last_activity')->index();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canned_messages');
        Schema::dropIfExists('live_chat_working_hours');
        Schema::dropIfExists('live_chat_configurations');
        Schema::dropIfExists('live_chat_settings');
        Schema::dropIfExists('user_attributes');
        Schema::dropIfExists('pre_approved_templates');
        Schema::dropIfExists('campaign_retries');
        Schema::dropIfExists('campaign_audience_attributes');
        Schema::dropIfExists('campaign_contacts');
        Schema::dropIfExists('campaign_audience');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('template_call_to_actions');
        Schema::dropIfExists('templates');
        Schema::dropIfExists('template_languages');
        Schema::dropIfExists('template_types');
        Schema::dropIfExists('template_categories');
        Schema::dropIfExists('contacts');
        Schema::dropIfExists('tag_messages');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('tag_categories');
        Schema::dropIfExists('options');
        Schema::dropIfExists('projects');
        Schema::dropIfExists('users');
        Schema::dropIfExists('countries');
        // Schema::dropIfExists('password_reset_tokens');
        // Schema::dropIfExists('sessions');
    }
};
