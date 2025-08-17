<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// learnlooma.com
// vuquickprep.com
// vuacademicease.com

Route::any('/login', ['as' => 'login', 'uses' => 'Dashboard\AuthController@login']);
Route::any('/register', ['as' => 'register', 'uses' => 'Dashboard\AuthController@register']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'Dashboard\AuthController@logout']);

// Auth::routes(['verify' => true]);
Route::group(['prefix' => 'dashboard', 'middleware' => ['user']], function() {

    Route::get('/', ['as' => 'dashboard', 'uses' => 'Dashboard\DashboardController@dashboard']);

    Route::any('/profile', ['as' => 'profile', 'uses' => 'Dashboard\ProfileController@index']);

    Route::get('/user', ['as' => 'users', 'uses' => 'Dashboard\ProfileController@user']);

    Route::get('/user/remove/{id}', ['as' => 'users.remove', 'uses' => 'Dashboard\ProfileController@remove']);

    Route::get('/user/suspend/{id}', ['as' => 'users.suspend', 'uses' => 'Dashboard\ProfileController@suspend']);

    Route::any('/magazines', ['as' => 'magazines', 'uses' => 'Dashboard\MagazineController@list']);

    Route::any('/magazines/edit/{id}', ['as' => 'magazines.edit', 'uses' => 'Dashboard\MagazineController@edit']);

    Route::get('/magazines/remove/{id}', ['as' => 'magazines.remove', 'uses' => 'Dashboard\MagazineController@remove']);

    Route::get('/articles', ['as' => 'articles', 'uses' => 'Dashboard\ArticleController@list']);

    Route::any('/articles/add/{dashboard?}', ['as' => 'articles.add', 'uses' => 'Dashboard\ArticleController@add']);

    Route::any('/articles/image-remove/{id}/{type}', ['as' => 'image_remove', 'uses' => 'Dashboard\ArticleController@image_remove']);

    Route::any('/articles/edit/{id}', ['as' => 'articles.edit', 'uses' => 'Dashboard\ArticleController@edit']);

    Route::get('/articles/remove/{id}', ['as' => 'articles.remove', 'uses' => 'Dashboard\ArticleController@remove']);

    Route::get('/articles/publish/{id}', ['as' => 'articles.publish', 'uses' => 'Dashboard\ArticleController@publish']);

    Route::any('/packages/add', ['as' => 'packages.add', 'uses' => 'Dashboard\PackageController@add']);

    Route::any('/packages/edit/{id}', ['as' => 'packages.edit', 'uses' => 'Dashboard\PackageController@edit']);

    Route::get('/packages/remove/{id}', ['as' => 'packages.remove', 'uses' => 'Dashboard\PackageController@remove']);

    Route::get('/packages', ['as' => 'packages', 'uses' => 'Dashboard\PackageController@packages']);

    Route::any('/charge/{id}', ['as' => 'charge', 'uses' => 'Dashboard\StripeController@charge']);

    Route::get('/stripe/success/{id}', ['as' => 'stripe.success', 'uses' => 'Dashboard\StripeController@success']);

    Route::get('/stripe/cancel/{id}', ['as' => 'stripe.cancel', 'uses' => 'Dashboard\StripeController@cancel']);

    Route::get('/google/sheets', ['as' => 'google.sheets', 'uses' => 'Dashboard\GoogleSheetController@index']);

    Route::get('/excel', ['as' => 'excel', 'uses' => 'Dashboard\ExcelController@excel']);

    Route::any('/excel/sheets', ['as' => 'excel.sheets', 'uses' => 'Dashboard\ExcelController@index']);

    Route::get('/notifications', ['as' => 'notifications', 'uses' => 'Dashboard\NotificationSendController@index']);

    Route::post('/store-token/{type}', ['as' => 'store.token', 'uses' => 'Dashboard\NotificationSendController@updateDeviceToken']);
    Route::post('/send-web-notification', ['as' => 'send.web-notification', 'uses' => 'Dashboard\NotificationSendController@sendNotification']);

    Route::get('/contact', ['as' => 'contact.list', 'uses' => 'Dashboard\ContactController@index']);

    Route::any('/contact/edit/{id}', ['as' => 'contact.edit', 'uses' => 'Dashboard\ContactController@edit']);

    Route::get('/contact/remove/{id}', ['as' => 'contact.remove', 'uses' => 'Dashboard\ContactController@remove']);

    Route::get('/comment', ['as' => 'comment.list', 'uses' => 'Dashboard\CommentController@index']);

    Route::any('/comment/reply/{id}', ['as' => 'comment.reply', 'uses' => 'Dashboard\CommentController@reply']);

    Route::any('/comment/edit/{id}', ['as' => 'comment.edit', 'uses' => 'Dashboard\CommentController@edit']);

    Route::get('/comment/publish/{id}', ['as' => 'comment.publish', 'uses' => 'Dashboard\CommentController@publish']);

    Route::get('/comment/remove/{id}', ['as' => 'comment.remove', 'uses' => 'Dashboard\CommentController@remove']);

});

Route::get('/', ['as' => 'front', 'uses' => 'Front\FrontController@front']);

Route::get('/article-detail/{slug}', ['as' => 'article.detail', 'uses' => 'Front\ArticleController@detail']);

Route::get('/article/all', ['as' => 'article.all', 'uses' => 'Front\ArticleController@all']);

Route::any('/search', ['as' => 'search.keyword', 'uses' => 'Front\SearchController@search']);

Route::get('/magazine-article/{slug}', ['as' => 'magazine.article', 'uses' => 'Front\MagazineController@list']);

Route::any('/contact-us', ['as' => 'contact', 'uses' => 'Front\ContactController@contact']);

Route::any('/comments/{slug}', ['as' => 'comment.add', 'uses' => 'Front\CommentController@add']);