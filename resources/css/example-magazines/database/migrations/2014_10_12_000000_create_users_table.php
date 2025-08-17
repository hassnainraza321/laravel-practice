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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->decimal('amount');
            $table->unsignedInteger('article_limit');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('username');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->boolean('is_admin')->default(false); // 1 for admin and 0 for members
            $table->boolean('account_status')->default(false); // 1 for active and 0 for suspend
            $table->foreignId('package_id')->nullable()->constrained('packages')->onDelete('cascade');
            $table->date('package_start')->nullable();
            $table->date('package_end')->nullable();
            $table->boolean('payment_status')->default(false); // 1 for complete and 0 for incomplete
            $table->string('transaction_id')->nullable();
            $table->text('api_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_logins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('ip_address');
            $table->string('browser');
            $table->string('platform');
            $table->boolean('device')->nullable(); // 1 for desktop and 0 for mobile
            $table->timestamp('last_login')->useCurrent();
            $table->timestamps();
        });

        Schema::create('magazines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('title');
            $table->string('slug');
            $table->text('content');
            $table->boolean('status')->default(false); // 1 is publish and 0 is for draft
            $table->boolean('request')->nullable(); // 1 is dashboard and 0 Api
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('mag_articles', function (Blueprint $table) {
            $table->foreignId('magazine_id');
            $table->foreignId('article_id');
            $table->primary(['magazine_id', 'article_id']);

            $table->foreign('magazine_id')->references('id')->on('magazines')->onDelete('cascade');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });

        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->index();
            $table->string('image');
            $table->boolean('featured_image')->default(false); // 1 is featured and 0 is for normal
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });

        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->index();
            $table->string('video');
            $table->boolean('featured_video')->default(false); // 1 is featured and 0 is for normal
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });

        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->index();
            $table->string('name');
            $table->string('path');
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });

        Schema::create('quizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->index();
            $table->text('question');
            $table->string('option_1', 100);
            $table->string('option_2', 100);
            $table->string('option_3', 100);
            $table->string('option_4', 100);
            $table->string('correct_option', 100);
            $table->boolean('save')->default(false); // 1 is saved and 0 is for not
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->index();
            $table->string('name');
            $table->string('email');
            $table->string('website');
            $table->text('comment');
            $table->boolean('status')->default(false); // 1 is publish and 0 is for draft
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
        });

        Schema::create('details', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('main_image')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->unsignedInteger('contact')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('linkedin')->nullable();
            $table->text('about_us')->nullable();
            $table->text('term_condition')->nullable();
            $table->text('privacy_policy')->nullable();
            $table->text('support')->nullable();
            $table->text('faqs')->nullable();
            $table->timestamps();
        });

        Schema::create('contact_us', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details');
        Schema::dropIfExists('contact_us');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('quizes');
        Schema::dropIfExists('documents');
        Schema::dropIfExists('videos');
        Schema::dropIfExists('images');
        Schema::dropIfExists('mag_articles');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('magazines');
        Schema::dropIfExists('user_logins');
        Schema::dropIfExists('users');
        Schema::dropIfExists('packages');
    }
};
