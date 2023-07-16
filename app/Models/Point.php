<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function alreadyExists($number, $raffleId){
        $exit = Point::where('number', $number)->where('raffle_id', $raffleId)->first();
        return $exit?true:false;
    }
    public function add($number, $raffleId, $clientId){
        $point = Point::create([
            'number'=>$number,
            'raffle_id'=>$raffleId,
            'client_id'=>$clientId
        ]);
        return $point;
    }
}
