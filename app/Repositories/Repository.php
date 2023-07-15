<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

abstract class Repository{
    protected $data;
    protected $user;
    public function __construct($request){
        $this->data = $request;
        $this->user = $request->user();
    }
}