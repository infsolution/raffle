<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function login(Request $request){
        $credentials = $request->only(['email', 'password']);
        Auth::attempt($credentials);
        $user = Auth::user();
        if(!$user){
            return response(['message'=>'unaltorized'], 401);
        }
        $token = $user->createToken('userToken')->accessToken;
        return response(['access_token'=>$token], 200);
    }
}
