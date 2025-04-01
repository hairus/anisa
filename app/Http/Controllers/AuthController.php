<?php

namespace App\Http\Controllers;

use App\Models\final_siswa;
use App\Models\smas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['password' => $request->password, 'username' => $request->username])) {

            $user = Auth::user();

            $final = $user->finalSIswa;

            $success['token'] =  $user->createToken('anisaApp')->plainTextToken;

            $success['name'] =  $user->name;

            $success["role"] = $user->role->role_id;

            $success["final"] = $user->finalisasi->final;

            $success["finalSiswa"] = $user->finalSIswa->final;

            return $success;
        } else {

            return response()->json(['error' => 'Unauthorised'], 401);
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
