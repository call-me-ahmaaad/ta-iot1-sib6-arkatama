<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['api.key'])->group(function () {
    Route::post('/dht11', [ApiController::class, 'api_dht11']);
    Route::post('/raindrop', [ApiController::class, 'api_raindrop']);
    Route::post('/mq2', [ApiController::class, 'api_mq2']);
});
