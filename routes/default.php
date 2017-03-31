<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// log in (via facebook)
Route::get('/auth/facebook/redirect', function (Request $request) {
    return Socialite::driver('facebook')->scopes([
        'email', 'user_likes',
    ])->redirect();
});

// check user (via facebook)
Route::get('/auth/facebook/check', function (Request $request) {
    $user = Socialite::driver('facebook')->user();

    return redirect()->route('facebook-token', ['token' => $user->token,]);
});

Route::get('/auth/facebook/token', function (Request $request) {
    $token = $request->input('token');

    $fb = new \Facebook\Facebook([
        'app_id' => config('services.facebook.client_id'),
        'app_secret' => config('services.facebook.client_secret'),
        'default_graph_version' => 'v2.8',
        'default_access_token' => $token,
    ]);

    $meRequest = $fb->get('/me');
    $meObj = $meRequest->getGraphObject();
    $id = $meObj->getField('id');
    $name = $meObj->getField('name');

    $request = $fb->get('/' . $id . '/music');
    $edge = $request->getGraphEdge();

    $res = '<pre>';
    $res .= $name . PHP_EOL . PHP_EOL;
    foreach ($edge as $node) {
        $res .= print_r($node->getField('name'), 1) . PHP_EOL;
    }
    $res .= '</pre>';

    return $res;
})->name('facebook-token');

// log in (via vk)
Route::get('/auth/vk/redirect', function (Request $request) {
    return Socialite::driver('vkontakte')->redirect();
});

// check user (via vk)
Route::get('/auth/vk/check', function (Request $request) {
    $user = Socialite::driver('vkontakte')->user();

    return redirect()->route('vk-token', ['token' => $user->token, 'user' => $user->id,]);
});

Route::get('/auth/vk/token', function (Request $request) {
    $token = $request->input('token');
    $id = $request->input('user');

    $vk = new \VK\VK(
        config('services.vkontakte.client_id'),
        config('services.vkontakte.client_secret'),
        $token);

    $userInfo = $vk->api('users.get', [
        'uids' => $id,
    ]);

    $userData = $userInfo['response'][0];

    $res = '<pre>';
    $res .= $userData['first_name'] . ' ' . $userData['last_name'] . PHP_EOL . PHP_EOL;

    $friendsInfo = $vk->api('friends.get', [
        'uid' => $id,
        'fields' => 'uid,first_name,last_name',
        'order' => 'name',
    ]);

    $friendsList = $friendsInfo['response'];

    foreach($friendsList as $friend) {
        $res .= $friend['uid'] . '  ' . $friend['first_name'] . ' ' . $friend['last_name'] . PHP_EOL;
    }

    $res .= '</pre>';

    return $res;
})->name('vk-token');