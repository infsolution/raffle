<?php

namespace App\Repositories;

use App\Http\Traits\HashTrait;
use App\Models\Client;
use App\Models\Image;
use App\Models\Point;
use App\Models\Raffle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RaffleRepository extends Repository{

    use HashTrait;

    public function __construct($request){
        parent::__construct($request);
    }
    public function all(){
        $data = [];
        $data['user'] = $this->user;
        $raffles = Raffle::where('user_id', $this->user->id)->with('images')->paginate();
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

        $raffle = Raffle::with('images', 'clients')->where('id',$raffleId)
        ->where('user_id', $this->user->id)->first();
        if(!$raffle){
            return response(['message'=>"não tem essa rifa"], 404);
        }
        $data['raffle'] = $raffle;
        return response($data, 200);
    }

    public function addeTraditinalPoints(array $poits){

    }

    public function addLotteryPoints(){
        $quantity = $this->data->input('quantity');  
        $raffleId = $this->data->input('raffle_id');
        $points = [];
        $client = $this->getClient($raffleId);
        for($i = 0; $i < $quantity; $i++){
            $point = $this->addPoint($client->id, $raffleId);
            array_push($points, intval($point->number));
        }
        if($client->historic_points){
            $historic = json_decode($client->historic_points);
            array_push($points, ...$historic);
        }
        $client->historic_points = json_encode($points, true);
        $client->save();
        return response($client, 201);
    }

    public function addPoint($clientId, $raffleId){
        $point = new Point();
        $number = $this->generateLotteryNumber();
        if($point->alreadyExists($number, $raffleId)){
            $number = $this->generateLotteryNumber();
            $this->addPoint($clientId, $raffleId);
        }else{
            return $point->add($number, $raffleId, $clientId);
        }
    }

    public function getClient($raffleId){
        $query = Client::where('name', $this->data->input('name'))
        ->where('raffle_id', $raffleId);
        if($this->data->input('email')){
            $query->where('email',$this->data->input('email'));
        }
        if($this->data->input('phone')){
            $query->where('phone',$this->data->input('phone'));
        }
        if($this->data->input('cpf')){
            $query->where('cpf',$this->data->input('cpf'));
        }
        $client = $query->first();

        if(!$client){
            $client = $this->createClient($raffleId);
        }
        return $client;
    }

    public function createClient($raffleId){
        $client = Client::create([
            'name'=>$this->data->input('name'),
            'email'=>$this->data->input('email')?$this->data->input('email'):null,
            'phone'=>$this->data->input('phone')?$this->data->input('phone'):null,
            'cpf'=>$this->data->input('cpf')?$this->data->input('cpf'):null,
            'raffle_id'=> $raffleId,
        ]);
        return $client;
    }
}