<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dht11;
use App\Models\Raindrop;

class SensorController extends Controller
{
    // Fungsi untuk menampilkan data dari database yang terbaru ke page dashboard
    public function dashboard(){
        $latestDhtData = Dht11::latest()->first();
        $latestRainData = Raindrop::latest()->first();

        return view('dashboard', [
            'temp_c' => $latestDhtData ? $latestDhtData->temp_c : null,
            'humid' => $latestDhtData ? $latestDhtData->humid : null,
            'rain_value' => $latestRainData ? $latestRainData->rain_value : null,
        ]);
    }

    // Fungsi untuk menampilkan data DHT11 dari database yang terbaru ke page dht11
    public function web_dht11(){
        $latestDhtData = Dht11::latest()->first();

        return view('dht11', [
            'temp_c' => $latestDhtData ? $latestDhtData->temp_c : null,
            'temp_f' => $latestDhtData ? $latestDhtData->temp_f : null,
            'temp_k' => $latestDhtData ? $latestDhtData->temp_k : null,
            'humid' => $latestDhtData ? $latestDhtData->humid : null,
        ]);
    }

    // Fungsi untuk menampilkan data temperature celcius dari database yang terbaru (update terus)
    public function latest_dht11(){
        $latestDhtData = Dht11::latest()->first();
        return response()->json([
            'temp_c' => $latestDhtData ? $latestDhtData->temp_c : 'No data available',
            'temp_f' => $latestDhtData ? $latestDhtData->temp_f : 'No data available',
            'temp_k' => $latestDhtData ? $latestDhtData->temp_k : 'No data available',
            'humid' => $latestDhtData ? $latestDhtData->humid : 'No data available',
        ]);

        return response()->json($data);
    }

    // Fungsi untuk menampilkan data temperature farenheit dari database yang terbaru (update terus)
    // public function latest_temp_f(){
    //     $latestDhtData = Dht11::latest()->first();
    //     return response()->json([
    //         'temp_f' => $latestDhtData ? $latestDhtData->temp_f : 'No data available',
    //     ]);
    // }

    // Fungsi untuk menampilkan data temperature kelvin dari database yang terbaru (update terus)
    // public function latest_temp_k(){
    //     $latestDhtData = Dht11::latest()->first();
    //     return response()->json([
    //         'temp_k' => $latestDhtData ? $latestDhtData->temp_k : 'No data available',
    //     ]);
    // }

    // Fungsi untuk menampilkan data humidity dari database yang terbaru (update terus)
    // public function latest_humid(){
    //     $latestDhtData = Dht11::latest()->first();
    //     return response()->json([
    //         'humid' => $latestDhtData ? $latestDhtData->humid : 'No data available',
    //     ]);
    // }

    // Fungsi untuk menampilkan data kondisi hujan dari database yang terbaru (update terus)
    public function latest_rain(){
        $latestRainData = Raindrop::latest()->first();
        return response()->json([
            'rain_value' => $latestRainData ? $latestRainData->rain_value : null,
        ]);
    }
}
