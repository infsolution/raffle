<?php

namespace App\Repositories;

abstract class Repository{
    protected $data;
    public function __construct($request){
        $this->data = $request;
    }
}