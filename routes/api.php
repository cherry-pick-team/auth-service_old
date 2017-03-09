<?php

use App\User;
use App\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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

// get current user
Route::get('/user', function (Request $request) {
    return ['user' => $request->user()];
});

$generateToken = function ($rand = null) {
    $generateKey = function ($rand = null) {
        return hash(
            'sha256',
            Crypt::encrypt(
                $rand . microtime() . '_user_token_' . rand()
            )
        );
    };

    return $generateKey($rand) . $generateKey(rand());
};

// log in
Route::post('/user/login/email', function (Request $request) use (&$generateToken) {
    $email = $request->input('email');
    $password = $request->input('password');

    $authData = [
        'email' => $email,
        'password' => $password,
    ];

    $auth = Auth::guard('default');

    if ($auth->once($authData)) {
        $user = $auth->user();

        $tokenKey = $generateToken(rand());

        $token = UserToken::create([
            'user_id' => $user->id,
            'token' => $tokenKey,
        ]);

        if ($token) {
            return [
                'user' => $user,
                'token' => $token->token,
            ];
        }

        abort(500, 'Couldn\'t create a token');
    }

    abort(403, 'Wrong email or password');
});

// sign up
Route::post('/user/signup/email', function (Request $request) use (&$generateToken) {
    $email = $request->input('email');
    $password = $request->input('password');
    $name = $request->input('name');

    if (empty($email) || empty($password) || empty($name)) {
        abort(400, 'Email, password or name is not given');
    }

    try {
        $user = User::create([
            'email' => $email,
            'name' => $name,
            'password' => Hash::make($password),
        ]);

        if (!is_object($user)) {
            abort(500, 'Couldn\'t create user');
        }
    } catch (Illuminate\Database\QueryException $e) {
        abort(400, 'User with such email already exists');
    }

    $tokenKey = $generateToken(rand());

    $token = UserToken::create([
        'user_id' => $user->id,
        'token' => $tokenKey,
    ]);

    if ($token) {
        return [
            'user' => $user,
            'token' => $token->token,
        ];
    }

    abort(500, 'Couldn\'t create a token');
});