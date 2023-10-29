<?php

namespace App\Repositories;

use App\Http\Traits\HashTrait;
use App\Models\Client;
use App\Models\Image;
use App\Models\Order;
use App\Models\Point;
use App\Models\Raffle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class RaffleRepository extends Repository
{

    use HashTrait;

    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        $raffles = Raffle::all();
        return response($raffles, 200);
    }
    public function all()
    {
        $data = [];
        $data['user'] = $this->user;
        $raffles = Raffle::where('user_id', $this->user->id)->with('images')->paginate();
        $data['raffles'] = $raffles;
        return response($data, 200);
    }

    public function create()
    {
        //To implement
    }

    public function store()
    {
        $data = $this->data->input();
        return DB::transaction(function () use ($data) {
            $raffle = Raffle::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'value_point' => $data['value_point'],
                'number_points' => $data['number_points'],
                'drawn_date' => $data['drawn_date'],
                'drawn_time' => $data['drawn_time'],
                'format' => $data['format'],
                'user_id' => $this->user->id
            ]);

            if ($this->data->hasFile('images')) {
                $images = $this->data->file('images');
                foreach ($images as $image) {
                    $extension = $image->getClientOriginalExtension();
                    $imageName = "{$raffle->id}" . date('YmdHms') . substr(microtime(true), 11, 4) . "." . $extension;
                    $path = $image->storeAs('images', $imageName);
                    Image::create([
                        'path' => config('app.url') . "/storage/$path",
                        'raffle_id' => $raffle->id
                    ]);
                }
            }
            return response($raffle, 201);
        });
    }

    public function show($raffleId)
    {
        $data = [];

        $raffle = Raffle::with('images', 'clients')->where('id', $raffleId)
            ->where('user_id', $this->user->id)->first();
        if (!$raffle) {
            return response(['message' => "não tem essa rifa"], 404);
        }
        $data['raffle'] = $raffle;
        return response($data, 200);
    }

    public function addeTraditinalPoints(array $poits)
    {
    }

    public function addLotteryPoints()
    {
        $quantity = $this->data->input('quantity');
        $raffleId = $this->data->input('raffle_id');
        $raffle = Raffle::find($raffleId);
        if ($raffle->total() + $quantity <= $raffle->number_points) {
            $client = $this->getClient($raffleId);
            $order = Order::create([
                'value' => $quantity * $raffle->value_point,
                'raffle_id' => $raffleId,
                'client_id' => $client->id
            ]);
            $reservation = array(
                "datetime" => date('Y-m-d H:i:s'),
                "quantity" => $quantity,
                "order" => $order->id
            );
            $saved = Redis::set("client_{$client->id}_raffle_{$raffleId}", json_encode($reservation));
            return response($order, 201);
        }
        return response(['message' => 'Raffle finished'], 401);
    }

    public function addPoint($clientId, $raffleId)
    {
        $number = $this->generateLotteryNumber();
        $exist = Redis::sIsMember("raffle_id_{$raffleId}_add_point", $number);
        if ($exist) {
            $number = $this->generateLotteryNumber();
            $this->addPoint($clientId, $raffleId);
        } else {
            return $number;
        }
    }

    public function getClient($raffleId)
    {
        $query = Client::where('name', $this->data->input('name'))
            ->where('raffle_id', $raffleId);
        if ($this->data->input('email')) {
            $query->where('email', $this->data->input('email'));
        }
        if ($this->data->input('phone')) {
            $query->where('phone', $this->data->input('phone'));
        }
        if ($this->data->input('cpf')) {
            $query->where('cpf', $this->data->input('cpf'));
        }
        $client = $query->first();

        if (!$client) {
            $client = $this->createClient($raffleId);
        }
        return $client;
    }

    public function createClient(int $raffleId): Client
    {
        $client = Client::firstOrCreate([
            'name' => $this->data->input('name'),
            'email' => $this->data->input('email') ? $this->data->input('email') : null,
            'phone' => $this->data->input('phone') ? $this->data->input('phone') : null,
            'cpf' => $this->data->input('cpf') ? $this->data->input('cpf') : null,
            'raffle_id' => $raffleId,
        ]);
        return $client;
    }

    public function addPayment()
    {
        $orderId = $this->data->input('order_id');
        $order = Order::where('id', $orderId)->where('paid', 0)->first();
        if (!$order) {
            return response(['message' => 'Ordem inexistente!'], 404);
        }
        $order->paid = true;
        $order->save();
        $data = Redis::get("client_{$order->client_id}_raffle_{$order->raffle_id}");
        $decodData = json_decode($data, true);
        $points = $this->addPoints($order->client_id, $order->raffle_id, $decodData['quantity']);
        return response($points, 201);
    }

    public function addPoints(int $clientId, int $raffleId, int $quantity)
    {
        $raffle = Raffle::find($raffleId);
        if (!$raffle) {
            return response(['message' => 'Rifa inexistente!'], 404);
        }
        $raffle->getAllPoints();
        $listPoints = [];
        for ($i = 1; $i <= $quantity; $i++) {
            $number = $this->addPoint($clientId, $raffle->id);
            array_push($listPoints, intval($number));
        }
        $point = $this->createOrUpdatePoint($listPoints, $quantity, $raffle->id, $clientId);
        Redis::del("raffle_id_{$raffleId}_add_point");
        return $point;
    }

    public function createOrUpdatePoint($listPoints, $quantity, $raffleId, $clientId)
    {
        $point = Point::where('client_id', $clientId)->first();
        if ($point) {
            $oldPoints = json_decode($point->numbers, true);
            array_push($oldPoints, ...$listPoints);
            $point->numbers = json_encode($oldPoints);
            $point->save();
        } else {
            $point = Point::create([
                'numbers' => json_encode($listPoints),
                'quantity' => $quantity,
                'paid' => true,
                'raffle_id' => $raffleId,
                'client_id' => $clientId
            ]);
        }
        return $point;
    }
}
