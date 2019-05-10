<?php

use Illuminate\Http\Request;
use Dingo\Api\Routing\Router;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

$api = app(Router::class);
// Only access tokens with the "read_user_data" scope will be given access.
$api->version('v1', ['middleware' => 'api.auth'], function($api) {

    $api->get('presentation', 'App\Api\V1\Controllers\World\PresentationController@presentations');
    $api->get('presentation/{id}', 'App\Api\V1\Controllers\World\PresentationController@one');

    $api->get('audiobook', 'App\Api\V1\Controllers\World\AudiobookController@audiobooks');
    $api->get('audiobook/{id}', 'App\Api\V1\Controllers\World\AudiobookController@audiobook');
    $api->get('audiobook/{id}/chapter', 'App\Api\V1\Controllers\World\AudiobookController@presentations');

    $api->get('story', 'App\Api\V1\Controllers\World\AudiobookController@audiobooks');
    $api->get('story/{id}', 'App\Api\V1\Controllers\World\AudiobookController@audiobook');
    $api->get('story/{id}/chapter', 'App\Api\V1\Controllers\World\AudiobookController@presentations');

    $api->get('presenter', 'App\Api\V1\Controllers\World\PresenterController@all');
    $api->get('presenter/{id}', 'App\Api\V1\Controllers\World\PresenterController@one');
    $api->get('presenter/{id}/presentation', 'App\Api\V1\Controllers\World\PresenterController@presentation ');

    $api->get('conference', 'App\Api\V1\Controllers\World\ConferenceController@all');
    $api->get('conference/{id}', 'App\Api\V1\Controllers\World\ConferenceController@one');
    $api->get('conference/{id}/presentation', 'App\Api\V1\Controllers\World\ConferenceController@presentations');

    $api->get('series', 'App\Api\V1\Controllers\World\SeriesController@all');
    $api->get('series/{id}', 'App\Api\V1\Controllers\World\SeriesController@one');
    $api->get('series/{id}/presentation', 'App\Api\V1\Controllers\World\SeriesController@presentations');

    $api->get('sponsor', 'App\Api\V1\Controllers\World\SponsorController@all');
    $api->get('sponsor/{id}', 'App\Api\V1\Controllers\World\SponsorController@one');
    $api->get('sponsor/{id}/presentation', 'App\Api\V1\Controllers\World\SponsorController@presentations');

    $api->get('topic', 'App\Api\V1\Controllers\World\TopicController@all');
    $api->get('topic/{id}', 'App\Api\V1\Controllers\World\TopicController@all');
    $api->get('topic/{id}/presentation', 'App\Api\V1\Controllers\World\TopicController@presentations');

    $api->get('music','App\Api\V1\Controllers\World\MusicController@latest');
    $api->get('music/albums','App\Api\V1\Controllers\World\MusicController@albums');
    $api->get('music/books','App\Api\V1\Controllers\World\MusicController@books');
    $api->get('music/mood','App\Api\V1\Controllers\World\MusicController@mood');
    $api->get('music/genre','App\Api\V1\Controllers\World\MusicController@genre');

    $api->group(['prefix' => 'tags'], function(Router $api) {
        $api->get('{site}','App\Api\V1\Controllers\World\SitesController@tags');
    });

    $api->group(['prefix' => 'auth'], function(Router $api) {

        $api->post('signup', 'App\Api\V1\Controllers\Auth\SignUpController@signUp');
        $api->post('signup/verify/{token}', 'App\Api\V1\Controllers\Auth\SignUpController@verify');
        $api->post('login', 'App\Api\V1\Controllers\Auth\LoginController@login');
        $api->post('recovery', 'App\Api\V1\Controllers\Auth\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\Api\V1\Controllers\Auth\ResetPasswordController@resetPassword');
        $api->get('user','App\Api\V1\Controllers\Auth\UserController@user');
        $api->post('user/update','App\Api\V1\Controllers\Auth\UserController@update');
        $api->post('update/password','App\Api\V1\Controllers\Auth\UserController@update_password');
    });

    $api->group(['prefix' => 'encode'], function(Router $api) {
        $api->post('addflavor', 'App\Api\V1\Controllers\Admin\EncodingController@addVideoFlavor');
    });

    $api->group(['prefix' => 'messenger'], function(Router $api) {
        $api->post('donation/confirmation', 'App\Api\V1\Controllers\Admin\EmailController@donation_confirmation');
    });

    $api->group(['prefix' => 'admin'], function(Router $api) {
      $api->get('conference', 'App\Api\V1\Controllers\Admin\ConferenceController@all');
      $api->get('sponsor', 'App\Api\V1\Controllers\Admin\SponsorController@all');
    });
});
