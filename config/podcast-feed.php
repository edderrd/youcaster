<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Defaults
     |--------------------------------------------------------------------------
     |
     | Default values that are used if the value is not not set
     |
     */

    'defaults' => [
        'title' => config('app.name') . ' Podcasts',
        'subtitle' => 'Listen your youtube videos as podcasts',
        'description' => 'Download youtube videos and listen offline',
        'link' => config('app.url'),
        'image' => config('app.url') . '/img/logo.png',
        'author' => 'YouCaster',
        'email' => 'youcaster@example.com',
        'category' => 'Technology',
        'language' => 'en-us',
        'copyright' => date('Y'),
    ],

];
