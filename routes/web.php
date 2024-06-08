<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\WhatsappController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/nav', function () {
    return view('navpage');
});

Route::get('/dashboard', [SensorController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/temp-and-humid', [SensorController::class, 'web_dht11'])->name('web.dht11');
Route::get('/gas-monitoring', [SensorController::class, 'web_mq2'])->name('web.mq2');
Route::get('/rain-monitoring', [SensorController::class, 'web_rain'])->name('web.rain');

Route::get('/latest-dht11', [SensorController::class, 'latest_dht11']);
Route::get('/latest-rain', [SensorController::class, 'latest_rain']);
Route::get('/latest-mq2', [SensorController::class, 'latest_mq2']);

// Route::get('/latest-temp_f', [SensorController::class, 'latest_temp_f']);
// Route::get('/latest-temp_k', [SensorController::class, 'latest_temp_k']);
// Route::get('/latest-humid', [SensorController::class, 'latest_humid']);
// Route::get('/latest-rain', [SensorController::class, 'latest_rain']);

// Route untuk mendapatkan data temperature terbaru
// Route::get('/latest-temp_c', function () {
//     $latestDhtData = Dht11::latest()->first();
//     return response()->json([
//         'temp_c' => $latestDhtData ? $latestDhtData->temp_c : 'No data available',
//     ]);
// })->middleware(['auth', 'verified']);

// Route::get('/latest-temp_f', function () {
//     $latestDhtData = Dht11::latest()->first();
//     return response()->json([
//         'temp_f' => $latestDhtData ? $latestDhtData->temp_f : 'No data available',
//     ]);
// })->middleware(['auth', 'verified']);

// Route::get('/latest-temp_k', function () {
//     $latestDhtData = Dht11::latest()->first();
//     return response()->json([
//         'temp_k' => $latestDhtData ? $latestDhtData->temp_k : 'No data available',
//     ]);
// })->middleware(['auth', 'verified']);

// Route untuk mendapatkan data humidity terbaru
// Route::get('/latest-humid', function () {
//     $latestDhtData = Dht11::latest()->first();
//     return response()->json([
//         'humid' => $latestDhtData ? $latestDhtData->humid : 'No data available',
//     ]);
// })->middleware(['auth', 'verified']);

// Route::get('/latest-rain', function () {
//     $latestRainData = Raindrop::latest()->first();
//     return response()->json([
//         'rain_value' => $latestRainData ? $latestRainData->rain_value : null,
//     ]);
// })->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

//* Route tidak terpakai

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/latest-data', function () {
//     $latestData = Dht11::latest()->first();
//     return response()->json([
//         'temp_c' => $latestData ? $latestData->temp_c : 'No data available',
//         'humid' => $latestData ? $latestData->humid : 'No data available'
//     ]);
// })->middleware(['auth', 'verified']);
