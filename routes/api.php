<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RaffleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/login',[AuthController::class, 'login']);

Route::middleware('auth:api')->prefix('v1')->group(function(){
    Route::get('raffle',[RaffleController::class, 'index']);
    Route::get('raffle/create',[RaffleController::class, 'create']);
    Route::post('raffle',[RaffleController::class, 'store']);
    Route::get('raffle/{raffle_id}',[RaffleController::class, 'show']);
});


Route::middleware('guest')->prefix('v1')->group(function(){
    Route::post('raffle/add_point', [RaffleController::class, 'addPoint']);
    Route::post('raffle/add_point', [RaffleController::class, 'addPoint']);
});