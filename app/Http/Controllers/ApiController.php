<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dht11;
use App\Models\Raindrop;

class ApiController extends Controller
{
    // Fungsi API untuk data sensor DHT11
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

    public function getLatestDht11Data()
    {
        $data = Dht11::latest()->first();
        return response()->json($data);
    }

    // Fungsi API untuk data sensor Raindrop Sensor
    public function api_raindrop(Request $request){
        $raindrop = new Raindrop;
        $raindrop->rain_value = $request->rain_value;
        $raindrop->save();

        // Mendapatkan ID produk yang baru saja ditambahkan
        $raindropId = $raindrop->id;

        // Membuat pesan JSON yang menyertakan data produk yang baru saja ditambahkan
        return response()->json([
            "message" => "Data Sensor Hujan Berhasil Ditambahkan",
            "data" => [
                "id" => $raindropId,
                "rain_value" => $raindrop->rain_value
            ]
        ], 201);
    }
}
