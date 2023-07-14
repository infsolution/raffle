<?php

namespace App\Http\Traits;

trait RegexTrait{
    public function isEmail(string $value){
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}