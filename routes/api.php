<?php
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
$api = app('Dingo\Api\Routing\Router');
// Only access tokens with the "read_user_data" scope will be given access.
$api->version('v1', function($api) {
   $api->group(['middleware' => 'auth:api'], function ($api) {
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

      $api->group(['prefix' => 'tags'], function($api) {
         $api->get('{site}','App\Api\V1\Controllers\World\SitesController@tags');
      });

      $api->group(['prefix' => 'auth'], function($api) {
         $api->post('signup', 'App\Api\V1\Controllers\Auth\SignUpController@signUp');
         $api->post('signup/verify/{token}', 'App\Api\V1\Controllers\Auth\SignUpController@verify');
         $api->post('login', 'App\Api\V1\Controllers\Auth\LoginController@login');
         $api->post('recovery', 'App\Api\V1\Controllers\Auth\ForgotPasswordController@sendResetEmail');
         $api->post('reset', 'App\Api\V1\Controllers\Auth\ResetPasswordController@resetPassword');
         $api->get('user','App\Api\V1\Controllers\Auth\UserController@user');
         $api->post('user/update','App\Api\V1\Controllers\Auth\UserController@update');
         $api->post('update/password','App\Api\V1\Controllers\Auth\UserController@update_password');
      });

      $api->group(['prefix' => 'encode'], function($api) {
         $api->post('addflavor', 'App\Api\V1\Controllers\Admin\EncodingController@addVideoFlavor');
      });

      $api->group(['prefix' => 'messenger'], function($api) {
         $api->post('donation/confirmation', 'App\Api\V1\Controllers\Admin\EmailController@donation_confirmation');
      });

      $api->group(['prefix' => 'admin'], function($api) {

         $api->get('audiobooks', 'App\Api\V1\Controllers\Admin\AudiobookController@allAudiobook');
         $api->post('audiobooks', 'App\Api\V1\Controllers\Admin\AudiobookController@createAudiobook');
         $api->put('audiobooks', 'App\Api\V1\Controllers\Admin\AudiobookController@updateAudiobook');
         $api->delete('audiobooks', 'App\Api\V1\Controllers\Admin\AudiobookController@deleteAudiobook');
         $api->get('audiobooks/chapters', 'App\Api\V1\Controllers\Admin\AudiobookController@allChapters');
         $api->get('audiobooks/chapters/{id}', 'App\Api\V1\Controllers\Admin\AudiobookController@chapters')->where(['id' => '[0-9]+']);
         $api->post('audiobooks/chapters', 'App\Api\V1\Controllers\Admin\AudiobookController@createChapter');
         $api->put('audiobooks/chapters', 'App\Api\V1\Controllers\Admin\AudiobookController@updateChapter');
         $api->delete('audiobooks/chapters', 'App\Api\V1\Controllers\Admin\AudiobookController@deleteChapter');
         $api->get('audiobooks/seriess', 'App\Api\V1\Controllers\Admin\AudiobookController@seriess');

         $api->get('stories', 'App\Api\V1\Controllers\Admin\StoryController@allStorybooks');
         $api->post('stories', 'App\Api\V1\Controllers\Admin\StoryController@createStorybook');
         $api->put('stories', 'App\Api\V1\Controllers\Admin\StoryController@updateStorybook');
         $api->delete('stories', 'App\Api\V1\Controllers\Admin\StoryController@deleteStorybook');
         $api->get('stories/chapters', 'App\Api\V1\Controllers\Admin\StoryController@allChapters');
         $api->get('stories/chapters/{id}', 'App\Api\V1\Controllers\Admin\StoryController@chapters')->where(['id' => '[0-9]+']);
         $api->post('stories/chapters', 'App\Api\V1\Controllers\Admin\StoryController@createChapter');
         $api->put('stories/chapters', 'App\Api\V1\Controllers\Admin\StoryController@updateChapter');
         $api->delete('stories/chapters', 'App\Api\V1\Controllers\Admin\StoryController@deleteChapter');
         $api->get('stories/seriess', 'App\Api\V1\Controllers\Admin\StoryController@seriess');

         $api->get('music', 'App\Api\V1\Controllers\Admin\MusicController@allAlbums');
         $api->post('music', 'App\Api\V1\Controllers\Admin\MusicController@createAlbum');
         $api->put('music', 'App\Api\V1\Controllers\Admin\MusicController@updateAlbum');
         $api->delete('music', 'App\Api\V1\Controllers\Admin\MusicController@deleteAlbum');
         $api->get('music/chapters', 'App\Api\V1\Controllers\Admin\MusicController@allChapters');
         $api->get('music/chapters/{id}', 'App\Api\V1\Controllers\Admin\MusicController@chapters')->where(['id' => '[0-9]+']);
         $api->post('music/chapters', 'App\Api\V1\Controllers\Admin\MusicController@createChapter');
         $api->put('music/chapters', 'App\Api\V1\Controllers\Admin\MusicController@updateChapter');
         $api->delete('music/chapters', 'App\Api\V1\Controllers\Admin\MusicController@deleteChapter');
         $api->get('music/seriess', 'App\Api\V1\Controllers\Admin\MusicController@seriess');

         $api->get('conferences', 'App\Api\V1\Controllers\Admin\ConferenceController@all');
         $api->get('conferences/{id}', 'App\Api\V1\Controllers\Admin\ConferenceController@one');
         $api->post('conferences', 'App\Api\V1\Controllers\Admin\ConferenceController@create');
         $api->put('conferences', 'App\Api\V1\Controllers\Admin\ConferenceController@update');
         $api->delete('conferences', 'App\Api\V1\Controllers\Admin\ConferenceController@delete');

         $api->get('presentations', 'App\Api\V1\Controllers\Admin\PresentationController@all');
         $api->get('presentations/{id}', 'App\Api\V1\Controllers\Admin\PresentationController@one');
         $api->post('presentations', 'App\Api\V1\Controllers\Admin\PresentationController@create');
         $api->put('presentations', 'App\Api\V1\Controllers\Admin\PresentationController@update');
         $api->delete('presentations', 'App\Api\V1\Controllers\Admin\PresentationController@delete');

         $api->group(['prefix' => 'legal'], function($api) {

            $api->get('agreements', 'App\Api\V1\Controllers\Admin\AgreementController@all');
            $api->get('agreements/{id}', 'App\Api\V1\Controllers\Admin\AgreementController@one');
            $api->get('agreements/{id}/presentations', 'App\Api\V1\Controllers\Admin\AgreementController@recordings');
            $api->post('agreements', 'App\Api\V1\Controllers\Admin\AgreementController@create');
            $api->put('agreements', 'App\Api\V1\Controllers\Admin\AgreementController@update');
            $api->delete('agreements', 'App\Api\V1\Controllers\Admin\AgreementController@delete');

            $api->get('licenses', 'App\Api\V1\Controllers\Admin\LicenseController@all');
            $api->get('licenses/{id}', 'App\Api\V1\Controllers\Admin\LicenseController@one');
            $api->post('licenses', 'App\Api\V1\Controllers\Admin\LicenseController@create');
            $api->put('licenses', 'App\Api\V1\Controllers\Admin\LicenseController@update');
            $api->delete('licenses', 'App\Api\V1\Controllers\Admin\LicenseController@delete');

            $api->get('releases', 'App\Api\V1\Controllers\Admin\LegalReleaseController@all');
            $api->get('releases/{id}', 'App\Api\V1\Controllers\Admin\LegalReleaseController@one');
            $api->post('releases', 'App\Api\V1\Controllers\Admin\LegalReleaseController@create');
            $api->put('releases', 'App\Api\V1\Controllers\Admin\LegalReleaseController@update');
            $api->delete('releases', 'App\Api\V1\Controllers\Admin\LegalReleaseController@delete');

            $api->get('presentations', 'App\Api\V1\Controllers\Admin\PresentationController@legalAll');
            $api->get('presentations/{id}', 'App\Api\V1\Controllers\Admin\PresentationController@legalOne');
            $api->put('presentations/{id}', 'App\Api\V1\Controllers\Admin\PresentationController@legalUpdate');

            $api->get('owners', 'App\Api\V1\Controllers\Admin\OwnerController@all');
            $api->get('owners/{id}', 'App\Api\V1\Controllers\Admin\OwnerController@one');
            $api->post('owners', 'App\Api\V1\Controllers\Admin\OwnerController@create');
            $api->put('owners', 'App\Api\V1\Controllers\Admin\OwnerController@update');
            $api->delete('owners', 'App\Api\V1\Controllers\Admin\OwnerController@delete');

            $api->get('terms', 'App\Api\V1\Controllers\Admin\LegalTermController@all');
            $api->get('terms/{id}', 'App\Api\V1\Controllers\Admin\LegalTermController@one');
            $api->post('terms', 'App\Api\V1\Controllers\Admin\LegalTermController@create');
            $api->put('terms', 'App\Api\V1\Controllers\Admin\LegalTermController@update');
            $api->delete('terms', 'App\Api\V1\Controllers\Admin\LegalTermController@delete');
         }); // End legal group

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

         $api->get('tags', 'App\Api\V1\Controllers\Admin\TagController@all');
         $api->post('tags', 'App\Api\V1\Controllers\Admin\TagController@create');
         $api->put('tags', 'App\Api\V1\Controllers\Admin\TagController@update');
         $api->delete('tags', 'App\Api\V1\Controllers\Admin\TagController@delete');

         $api->get('tags/categories', 'App\Api\V1\Controllers\Admin\TagCategoryController@all');
         $api->post('tags/categories', 'App\Api\V1\Controllers\Admin\TagCategoryController@create');
         $api->put('tags/categories', 'App\Api\V1\Controllers\Admin\TagCategoryController@update');
         $api->delete('tags/categories', 'App\Api\V1\Controllers\Admin\TagCategoryController@delete');

         $api->get('topics', 'App\Api\V1\Controllers\Admin\TopicController@all');
         $api->get('topics/{id}/presentations', 'App\Api\V1\Controllers\Admin\TopicController@presentations');
         $api->post('topics', 'App\Api\V1\Controllers\Admin\TopicController@create');
         $api->put('topics', 'App\Api\V1\Controllers\Admin\TopicController@update');
         $api->delete('topics', 'App\Api\V1\Controllers\Admin\TopicController@delete');
      }); // End protected route.
   }); // End admin group.
});
