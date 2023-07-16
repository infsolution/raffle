<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raffle extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function images(){
        return $this->hasMany(Image::class, 'raffle_id');
    }

    public function clients(){
        return $this->hasMany(Client::class, 'raffle_id');
    }
}
