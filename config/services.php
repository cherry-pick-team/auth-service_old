<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'facebook' => [
        'client_id' => '1763856620597247',
        'client_secret' => '1049ae592a84710ece058db6614b14ce',
        'redirect' => 'http://localhost:8080/auth/facebook/check',
    ],

    'vkontakte' => [
        'client_id' => '5914977',
        'client_secret' => 'QTKwgLwqjF5lkeKw2RoZ',
        'redirect' => 'http://localhost:8080/auth/vk/check',
    ],
];