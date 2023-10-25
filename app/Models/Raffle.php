<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Raffle extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function images()
    {
        return $this->hasMany(Image::class, 'raffle_id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'raffle_id');
    }

    public function points()
    {
        return $this->hasMany(Point::class, 'raffle_id');
    }


    public function total(): int
    {
        $total = 0;
        $points = $this->points;
        foreach ($points as $point) {
            $total += $point->quantity;
        }
        return $total;
    }

    public function getAllPoints()
    {
        $points = $this->points();
        foreach ($points as $point) {
            $numbers = json_decode($point->numbers, true);
            foreach ($numbers as $number) {
                Redis::sAdd("raffle_id_{$this->id}_add_point", $number);
            }
        }
    }
}
