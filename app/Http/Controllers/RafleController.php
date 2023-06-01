<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rafle;
use Illuminate\Support\Facades\Auth;


class RafleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $raffles = Rafle::where('user_id', Auth::id())->get();
        return view('dashboard', ['raffles' => $raffles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('raffle.new');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge(['user_id'=> Auth::id()]);
        Rafle::create($request->input());

        return redirect('/dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $raffle = Rafle::find($id);
        return view('raffle.raffle', ['raffle'=>$raffle]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $raffle = Rafle::find($id);
        return view('raffle.raffle', ['raffle'=>$raffle]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $raffle = Rafle::find($id);
        $raffle->delete();
        return redirect('/dashboard'); 
    }
}
