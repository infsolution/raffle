<?php

namespace App\Repositories;

use App\Http\Traits\RegexTrait;
use App\Repositories\Repository;
use Illuminate\Support\Facades\Auth;


class AuthRepository extends Repository{

    use RegexTrait;

    public function __construct($request){
        parent::__construct($request);
    }

    
    public function authenticate(){
        $username = $this->data->input('username');
        $password = $this->data->input('password');
        $credentials = array('password'=>$password);
        if($this->isEmail($username)){
            $credentials['email'] = $username;
        }else{
            $credentials['phone'] = $username;
        }
        Auth::attempt($credentials);
        $user = Auth::user();
         if(!$user){
            return response(['message'=>'unaltorized'], 401);
        }
        $token = $user->createToken('userToken')->accessToken;
        return response(['access_token'=>$token], 200);
    }

}