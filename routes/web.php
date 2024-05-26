<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Dht11Controller;
use Illuminate\Support\Facades\Route;

use App\Models\Dht11;

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

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [Dht11Controller::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route::get('/latest-data', function () {
//     $latestData = Dht11::latest()->first();
//     return response()->json([
//         'temp_c' => $latestData ? $latestData->temp_c : 'No data available',
//         'humid' => $latestData ? $latestData->humid : 'No data available'
//     ]);
// })->middleware(['auth', 'verified']);

// Route untuk mendapatkan data temperature terbaru
Route::get('/latest-temp', function () {
    $latestData = Dht11::latest()->first();
    return response()->json([
        'temp_c' => $latestData ? $latestData->temp_c : 'No data available',
    ]);
})->middleware(['auth', 'verified']);

// Route untuk mendapatkan data humidity terbaru
Route::get('/latest-humid', function () {
    $latestData = Dht11::latest()->first();
    return response()->json([
        'humid' => $latestData ? $latestData->humid : 'No data available',
    ]);
})->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
