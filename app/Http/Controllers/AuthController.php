<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            $user = Auth::user();

            $success['token'] =  $user->createToken('MyApp')->plainTextToken;

            $success['name'] =  $user->name;

            $success["role"] = $user->role->role_id;

            return $success;
        } else {

            return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
        }
    }

    public function logout(Request $request)
    {
        if ($token = $request->bearerToken()) {
            $model = Sanctum::$personalAccessTokenModel;
            $accessToken = $model::findToken($token);
            $accessToken->delete();
        }
        return 'logged out';
    }
}
