<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// log in (via facebook)
Route::get('/auth/facebook/redirect', function (Request $request) {
    return Socialite::driver('facebook')->redirect();
});

// check user (via facebook)
Route::get('/auth/facebook/check', function (Request $request) {
    $user = Socialite::driver('facebook')->user();
    return '<pre>' . print_r($user, 1) . '</pre>';
});

// log in (via vk)
Route::get('/auth/vk/redirect', function (Request $request) {
    return Socialite::driver('vkontakte')->redirect();
});

// check user (via vk)
Route::get('/auth/vk/check', function (Request $request) {
    $user = Socialite::driver('vkontakte')->user();
    return '<pre>' . print_r($user, 1) . '</pre>';
});