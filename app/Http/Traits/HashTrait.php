<?php

namespace App\Http\Traits;

trait HashTrait
{
    public function generateLotteryNumber()
    {
        $number = '';
        for ($i = 0; $i < 6; $i++) {
            $number .= random_int(0, 9);
        }
        return $number;
    }
}
