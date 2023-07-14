<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function login(Request $request){
        return (new AuthRepository($request))->authenticate();
        
        // $credentials = $request->only(['phone', 'password']);
        // Auth::attempt($credentials);
        // $user = Auth::user();

        // dd($user);
        // if(!$user){
        //     return response(['message'=>'unaltorized'], 401);
        // }
        // $token = $user->createToken('userToken')->accessToken;
        // return response(['access_token'=>$token], 200);
    }

    
}
