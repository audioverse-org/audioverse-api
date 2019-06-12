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

    $api->get('presentations', 'App\Api\V1\Controllers\World\PresentationController@presentations');
    $api->get('presentations/{id}', 'App\Api\V1\Controllers\World\PresentationController@one');

    $api->get('audiobooks', 'App\Api\V1\Controllers\World\AudiobookController@audiobooks');
    $api->get('audiobooks/{id}', 'App\Api\V1\Controllers\World\AudiobookController@audiobook');
    $api->get('audiobooks/{id}/chapters', 'App\Api\V1\Controllers\World\AudiobookController@presentations');

    $api->get('stories', 'App\Api\V1\Controllers\World\AudiobookController@audiobooks');
    $api->get('stories/{id}', 'App\Api\V1\Controllers\World\AudiobookController@audiobook');
    $api->get('stories/{id}/chapters', 'App\Api\V1\Controllers\World\AudiobookController@presentations');

    $api->get('presenters', 'App\Api\V1\Controllers\World\PresenterController@all');
    $api->get('presenters/{id}', 'App\Api\V1\Controllers\World\PresenterController@one');
    $api->get('presenters/{id}/presentations', 'App\Api\V1\Controllers\World\PresenterController@presentation ');

    $api->get('conferences', 'App\Api\V1\Controllers\World\ConferenceController@all');
    $api->get('conferences/{id}', 'App\Api\V1\Controllers\World\ConferenceController@one');
    $api->get('conferences/{id}/presentations', 'App\Api\V1\Controllers\World\ConferenceController@presentations');

    $api->get('seriess', 'App\Api\V1\Controllers\World\SeriesController@all');
    $api->get('seriess/{id}', 'App\Api\V1\Controllers\World\SeriesController@one');
    $api->get('seriess/{id}/presentations', 'App\Api\V1\Controllers\World\SeriesController@presentations');

    $api->get('sponsors', 'App\Api\V1\Controllers\World\SponsorController@all');
    $api->get('sponsors/{id}', 'App\Api\V1\Controllers\World\SponsorController@one');
    $api->get('sponsors/{id}/presentations', 'App\Api\V1\Controllers\World\SponsorController@presentations');

    $api->get('topics', 'App\Api\V1\Controllers\World\TopicController@all');
    $api->get('topics/{id}', 'App\Api\V1\Controllers\World\TopicController@all');
    $api->get('topics/{id}/presentations', 'App\Api\V1\Controllers\World\TopicController@presentations');

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

      $api->get('agreements', 'App\Api\V1\Controllers\Admin\AgreementController@all');
      $api->get('agreements/{id}', 'App\Api\V1\Controllers\Admin\AgreementController@one');
      $api->get('agreements/{id}/recordings', 'App\Api\V1\Controllers\Admin\AgreementController@recordings');
      $api->post('agreements', 'App\Api\V1\Controllers\Admin\AgreementController@create');
      $api->put('agreements', 'App\Api\V1\Controllers\Admin\AgreementController@update');
      $api->delete('agreements', 'App\Api\V1\Controllers\Admin\AgreementController@delete');

      $api->get('conferences', 'App\Api\V1\Controllers\Admin\ConferenceController@all');
      $api->get('conferences/{id}', 'App\Api\V1\Controllers\Admin\ConferenceController@one');
      $api->post('conferences', 'App\Api\V1\Controllers\Admin\ConferenceController@create');
      $api->put('conferences', 'App\Api\V1\Controllers\Admin\ConferenceController@update');
      $api->delete('conferences', 'App\Api\V1\Controllers\Admin\ConferenceController@delete');

      $api->get('licenses', 'App\Api\V1\Controllers\Admin\LicenseController@all');
      $api->get('licenses/{id}', 'App\Api\V1\Controllers\Admin\LicenseController@one');
      $api->post('licenses', 'App\Api\V1\Controllers\Admin\LicenseController@create');
      $api->put('licenses', 'App\Api\V1\Controllers\Admin\LicenseController@update');
      $api->delete('licenses', 'App\Api\V1\Controllers\Admin\LicenseController@delete');

      $api->get('owners', 'App\Api\V1\Controllers\Admin\OwnerController@all');
      $api->get('owners/{id}', 'App\Api\V1\Controllers\Admin\OwnerController@one');
      $api->post('owners', 'App\Api\V1\Controllers\Admin\OwnerController@create');
      $api->put('owners', 'App\Api\V1\Controllers\Admin\OwnerController@update');
      $api->delete('owners', 'App\Api\V1\Controllers\Admin\OwnerController@delete');

      $api->get('presenters', 'App\Api\V1\Controllers\Admin\PresenterController@all');
      $api->get('presenters/mass', 'App\Api\V1\Controllers\Admin\PresenterController@mass');
      $api->get('presenters/{id}', 'App\Api\V1\Controllers\Admin\PresenterController@one');
      $api->post('presenters', 'App\Api\V1\Controllers\Admin\PresenterController@create');
      $api->put('presenters', 'App\Api\V1\Controllers\Admin\PresenterController@update');
      $api->delete('presenters', 'App\Api\V1\Controllers\Admin\PresenterController@delete');

      $api->get('seriess', 'App\Api\V1\Controllers\Admin\SeriesController@all');
      $api->get('seriess/{id}', 'App\Api\V1\Controllers\Admin\SeriesController@one');
      $api->post('seriess', 'App\Api\V1\Controllers\Admin\SeriesController@create');
      $api->put('seriess', 'App\Api\V1\Controllers\Admin\SeriesController@update');
      $api->delete('seriess', 'App\Api\V1\Controllers\Admin\SeriesController@delete');

      $api->get('sponsors', 'App\Api\V1\Controllers\Admin\SponsorController@all');
      $api->get('sponsors/{id}', 'App\Api\V1\Controllers\Admin\SponsorController@one');
      $api->post('sponsors', 'App\Api\V1\Controllers\Admin\SponsorController@create');
      $api->put('sponsors', 'App\Api\V1\Controllers\Admin\SponsorController@update');
      $api->delete('sponsors', 'App\Api\V1\Controllers\Admin\SponsorController@delete');

      $api->get('terms', 'App\Api\V1\Controllers\Admin\LegalTermController@all');
      $api->get('terms/{id}', 'App\Api\V1\Controllers\Admin\LegalTermController@one');
      $api->post('terms', 'App\Api\V1\Controllers\Admin\LegalTermController@create');
      $api->put('terms', 'App\Api\V1\Controllers\Admin\LegalTermController@update');
      $api->delete('terms', 'App\Api\V1\Controllers\Admin\LegalTermController@delete');
    });
});
