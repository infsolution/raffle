<?php

namespace App\Http\Controllers;

use App\Http\Requests\RaffleRequest;
use App\Models\Raffle;
use App\Repositories\RaffleRepository;
use Illuminate\Http\Request;

class RaffleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return (new RaffleRepository($request))->index();
    }


    public function all(Request $request)
    {
        return (new RaffleRepository($request))->all();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RaffleRequest $request)
    {
        return (new RaffleRepository($request))->store();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $raffleId)
    {
        return (new RaffleRepository($request))->show($raffleId);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Raffle $raffle)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Raffle $raffle)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Raffle $raffle)
    {
        //
    }


    public function addPoint(Request $request)
    {
        return (new RaffleRepository($request))->addLotteryPoints();
    }

    public function addPayment(Request $request)
    {
        return (new RaffleRepository($request))->addPayment();
    }
}
