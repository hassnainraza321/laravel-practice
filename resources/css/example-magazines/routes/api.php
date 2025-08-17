<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Personal access client created successfully.
// Client ID: 1
// Client secret: yg3kSHw3uJyX6QUzyVvhjL3zBLhWJGyPsvUbpkvk
// Password grant client created successfully.
// Client ID: 2
// Client secret: rZSrx4qZqGB61AYMSOJFC1RCpbKddFcgNBV3Ajzm


// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/api/login', ['as' => 'login.api', 'uses' => 'Api\AuthController@login']);
Route::post('/api/register', ['as' => 'register.api', 'uses' => 'Api\AuthController@register']);
Route::get('/articles/all', ['as' => 'api.articles.all', 'uses' => 'Api\ArticleController@all']);

route::group(['middleware' => ['auth:api']], function() {

    Route::get('/articles', ['as' => 'api.articles', 'uses' => 'Api\ArticleController@list']);
    Route::post('/articles/add', ['as' => 'api.articles.add', 'uses' => 'Api\ArticleController@add']);
    Route::get('/articles/edit/{id}', ['as' => 'api.articles.edit', 'uses' => 'Api\ArticleController@edit']);
    Route::put('/articles/edit/{id}', ['as' => 'api.articles.put', 'uses' => 'Api\ArticleController@edit']);
    Route::delete('/articles/remove/{id}', ['as' => 'api.articles.remove', 'uses' => 'Api\ArticleController@remove']);
    Route::delete('/articles/image-remove/{id}/{type}', ['as' => 'api.image_remove', 'uses' => 'Api\ArticleController@image_remove']);
    Route::get('/magazines', ['as' => 'api.magazines', 'uses' => 'Api\MagazineController@list']);
    Route::post('/magazines', ['as' => 'api.magazines.post', 'uses' => 'Api\MagazineController@list']);
    Route::get('/magazines/edit/{id}', ['as' => 'api.magazines.edit', 'uses' => 'Api\MagazineController@edit']);
    Route::put('/magazines/edit/{id}', ['as' => 'api.magazines.put', 'uses' => 'Api\MagazineController@edit']);
    Route::delete('/magazines/remove/{id}', ['as' => 'api.magazines.remove', 'uses' => 'Api\MagazineController@remove']);
    Route::get('/packages/add', ['as' => 'api.packages.add', 'uses' => 'Api\PackageController@add']);
    Route::post('/packages/add', ['as' => 'api.packages.post', 'uses' => 'Api\PackageController@add']);
    Route::get('/packages/edit/{id}/{type}', ['as' => 'api.packages.edit', 'uses' => 'Api\PackageController@edit']);
    Route::put('/packages/edit/{id}/{type}', ['as' => 'api.packages.put', 'uses' => 'Api\PackageController@edit']);
    Route::get('/packages/remove/{id}', ['as' => 'api.packages.remove', 'uses' => 'Api\PackageController@remove']);

    Route::get('/packages', ['as' => 'api.packages', 'uses' => 'Api\PackageController@packages']);

});