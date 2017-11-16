<?php

return [

    /*
    |--------------------------------------------------------------------------
    | List page size
    |--------------------------------------------------------------------------
    |
    | This value is determines number of records returned to the application
    */
    'page_size' => 25,

    /*
    |--------------------------------------------------------------------------
    | Default language
    |--------------------------------------------------------------------------
    |
    | This value is determines the default language to return when language is
    | not specified
    */
    'default_lang' => 'en',

    /*
    |--------------------------------------------------------------------------
    | Static URL
    |--------------------------------------------------------------------------
    | Static URL for assets
    |
    */
    'static_url' => env('AVORG_STATIC_BASE_URL','https://s.audioverse.org'),
    /* base for sites image url */
    'static_sites_url' => env('AVORG_SITE_IMAGE_BASE','https://s.audioverse.org'),
    'website_url' => env('AVORG_WBSITE_URL','https://www.audioverse.org'),

    'verify_email_page_url' => env('AVORG_WBSITE_URL','https://www.audioverse.org') . '/account/verify?token=',

    'password_reset_page_url' => env('AVORG_WBSITE_URL','https://www.audioverse.org') . '/account/reset?token=',

    'lang_hash' => [
        'en' => 'english',
        'es' => 'espanol',
        'de' => 'deutsch',
        'zh' => 'zhongwen',
        'fr' => 'francais',
        'ja' => 'ja',
    ],

    'contact_email' => env('AVORG_DONATION_EMAIL','contact@audioverse.org'),
    /*
    |--------------------------------------------------------------------------
    | Content Types
    |--------------------------------------------------------------------------
    |
    */
    'content_type' => [
        'presentation' => 1,
        'music' => 2,
        'book' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Grant Tokens for AudioVerse API authentication for internal use
    |--------------------------------------------------------------------------
    |
    | The OAuth2 password grant allows your other first-party clients, such as a mobile application,
    | to obtain an access token using an e-mail address / username and password. This allows you to issue access tokens
    | securely to your first-party clients without requiring your users to go through the entire OAuth2 authorization
    | code redirect flow.
    */

    'oauth_password_grant' => [
        'client_id' => env('OAUTH_PASSWORD_GRANT_CLIENT_ID', 0),
        'client_secret' => env('OAUTH_PASSWORD_GRANT_CLIENT_SECRET', 0),
    ],

    'sign_up' => [
        'validation_rules' => [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6'
        ]
    ],

    'login' => [
        'validation_rules' => [
            'email' => 'required|email',
            'password' => 'required'
        ]
    ],

    'forgot_password' => [
        'validation_rules' => [
            'email' => 'required|email'
        ]
    ],

    'reset_password' => [
        'validation_rules' => [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]
    ],

    'donation_email' => [
        'validation_rules' => [
            'amount' => 'required',
            'email' => 'required|email',
            'is_recurring' => 'required',
            'first_name' => 'required',
            'last_name' => 'required'
        ]
    ],

    'add_video_flavor' => [
        'validation_rules' => [
            'recordingId' => 'required',
            'filename' => 'required',
            'filesize' => 'required',
            'duration' => 'required',
            'bitrate' => 'required',
            'width' => 'required',
            'height' => 'required',
            'container' => 'required'
        ]
    ],

    'tags' => [
        'validation_rules' => [
            'tags' => 'required'
        ],
    ],

    'site_tag_category_id' => env('SITE_TAG_CATEGORY_ID')
];