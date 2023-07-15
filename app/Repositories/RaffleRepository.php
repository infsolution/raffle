<?php

namespace App\Repositories;

use App\Models\Image;
use App\Models\Raffle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RaffleRepository extends Repository{
    public function __construct($request){
        parent::__construct($request);
    }
    public function all(){
        $data = [];
        $data['user'] = $this->user;
        $raffles = Raffle::where('user_id', $this->user->id)->with('images')->get();
        $data['raffles'] = $raffles;
        return response($data, 200);
    }

    public function create(){
        //To implement
    }

    public function store(){
        $data = $this->data->input();
        return DB::transaction(function() use($data){
            $raffle = Raffle::create([
                'title'=>$data['title'],
                'description'=>$data['description'],
                'value_point'=>$data['value_point'],
                'number_points'=>$data['number_points'],
                'drawn_date'=>$data['drawn_date'],
                'drawn_time'=>$data['drawn_time'],
                'format'=>$data['format'],
                'user_id'=>$this->user->id
            ]);

            if($this->data->hasFile('images')){
                $images = $this->data->file('images');
                foreach($images as $image){
                    $extension = $image->getClientOriginalExtension();
                    $imageName = "{$raffle->id}".date('YmdHms').substr(microtime(true),11,4).".".$extension;
                    $path = $image->storeAs('images',$imageName);
                    Image::create([
                        'path'=>config('app.url')."/storage/$path",
                        'raffle_id'=>$raffle->id
                    ]);
                }
            }
            return response($raffle, 201);
        });
    }

    public function show($raffleId){
        $data = [];

        $raffle = Raffle::with('images')->where('id',$raffleId)
        ->where('user_id', $this->user->id)->first();
        if(!$raffle){
            return response(['message'=>"não tem essa rifa"], 404);
        }
        $data['raffle'] = $raffle;
        return response($data, 200);
    }
}