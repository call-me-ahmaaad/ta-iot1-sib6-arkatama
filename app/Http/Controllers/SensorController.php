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
        $latestMq2Data = Mq2::latest()->first();

        return view('dashboard', [
            'temp_c' => $latestDhtData ? $latestDhtData->temp_c : null,
            'humid' => $latestDhtData ? $latestDhtData->humid : null,
            'rain_value' => $latestRainData ? $latestRainData->rain_value : null,
            'gas_value' => $latestMq2Data ? $latestMq2Data->gas_value : null,
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
            'temp_c' => $latestDhtData ? $latestDhtData->temp_c : null,
            'temp_f' => $latestDhtData ? $latestDhtData->temp_f : null,
            'temp_k' => $latestDhtData ? $latestDhtData->temp_k : null,
            'humid' => $latestDhtData ? $latestDhtData->humid : null,
            'created_at' => $latestDhtData ? $latestDhtData->created_at : null,
        ]);
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

    public function latest_mq2(){
        $latestMq2Data = Mq2::latest()->first();
        return response()->json([
            'gas_value' => $latestMq2Data ? $latestMq2Data->gas_value : null,
        ]);
    }
}
