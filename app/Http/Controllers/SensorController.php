<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dht11;
use App\Models\Raindrop;
use App\Models\Mq2;

class SensorController extends Controller
{
    // Fungsi untuk menampilkan data dari database yang terbaru ke page dashboard
    public function dashboard(){
        $latestDhtData = Dht11::latest()->first();
        $latestRainData = Raindrop::latest()->first();
        $latestMq2Data = Mq2::latest()->first();

        return view('dashboard', [
            'title' => 'Dashboard',
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
            'title' => 'DHT11 Monitoring Page',
            'temp_c' => $latestDhtData ? $latestDhtData->temp_c : null,
            'temp_f' => $latestDhtData ? $latestDhtData->temp_f : null,
            'temp_k' => $latestDhtData ? $latestDhtData->temp_k : null,
            'humid' => $latestDhtData ? $latestDhtData->humid : null,
        ]);
    }

    public function web_mq2(){
        $latestMq2Data = Mq2::latest()->first();

        return view('mq2', [
            'title' => 'MQ-5 Monitoring Page',
            'gas_value' => $latestMq2Data ? $latestMq2Data->gas_value : null,
        ]);
    }

    public function web_rain(){
        $latestRainData = Raindrop::latest()->first();

        return view('rainsensor', [
            'title' => 'Raindrop Monitoring Page',
            'rain_value' => $latestRainData ? $latestRainData->rain_value : null,
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

    // Fungsi untuk menampilkan data kondisi hujan dari database yang terbaru (update terus)
    public function latest_rain(){
        $latestRainData = Raindrop::latest()->first();
        return response()->json([
            'rain_value' => $latestRainData ? $latestRainData->rain_value : null,
            'created_at' => $latestRainData ? $latestRainData->created_at : null,
        ]);
    }

    public function latest_mq2(){
        $latestMq2Data = Mq2::latest()->first();
        return response()->json([
            'gas_value' => $latestMq2Data ? $latestMq2Data->gas_value : null,
            'created_at' => $latestMq2Data ? $latestMq2Data->created_at : null,
        ]);
    }
}
