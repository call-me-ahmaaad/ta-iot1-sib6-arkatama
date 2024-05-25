<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Raindrop;

class RaindropController extends Controller
{
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
