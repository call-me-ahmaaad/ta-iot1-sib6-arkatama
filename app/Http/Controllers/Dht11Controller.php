<?php

namespace App\Http\Controllers;

use App\Models\Dht11;
use App\Models\Raindrop;

use Illuminate\Http\Request;

class Dht11Controller extends Controller
{
    public function dashboard(){
        $latestDhtData = Dht11::latest()->first();
        $latestRainData = Raindrop::latest()->first();

        return view('dashboard', [
            'temp_c' => $latestDhtData ? $latestDhtData->temp_c : null,
            'humid' => $latestDhtData ? $latestDhtData->humid : null,
            'rain_value' => $latestRainData ? $latestRainData->rain_value : null,
        ]);
    }

    public function api_dht11(Request $request){
        $dht11 = new Dht11;
        $dht11->temp_c = $request->temp_c;
        $dht11->temp_f = $request->temp_f;
        $dht11->temp_k = $request->temp_k;
        $dht11->humid = $request->humid;
        $dht11->save();

        // Mendapatkan ID produk yang baru saja ditambahkan
        $dht11Id = $dht11->id;

        // Membuat pesan JSON yang menyertakan data produk yang baru saja ditambahkan
        return response()->json([
            "message" => "Data Temperature Berhasil Ditambahkan",
            "data" => [
                "id" => $dht11Id,
                "temp_c" => $dht11->temp_c,
                "temp_f" => $dht11->temp_f,
                "temp_k" => $dht11->temp_k,
                "humid" => $dht11->humid
            ]
        ], 201);
    }

    public function web_dht11(){
        $latestDhtData = Dht11::latest()->first();

        return view('dht11', [
            'temp_c' => $latestDhtData ? $latestDhtData->temp_c : null,
            'temp_f' => $latestDhtData ? $latestDhtData->temp_f : null,
            'temp_k' => $latestDhtData ? $latestDhtData->temp_k : null,
            'humid' => $latestDhtData ? $latestDhtData->humid : null,
        ]);
    }
}
