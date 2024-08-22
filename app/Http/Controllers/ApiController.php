<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dht11;
use App\Models\Raindrop;
use App\Models\Mq2;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

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

    // Fungsi API untuk data sensor MQ-2 Sensor
    public function api_mq2(Request $request){
        $mq2 = new Mq2;
        $mq2->gas_value = $request->gas_value;
        $mq2->save();

        // Mendapatkan data terbaru dari sensor lainnya
        $raindrop = Raindrop::latest()->first();
        $dht11 = Dht11::latest()->first();

        // Mengecek apakah temp_c melebihi 70
        if ($mq2->gas_value > 1400) {
            $this->sendWhatsAppNotification($dht11->temp_c ?? 'N/A', $dht11->temp_f ?? 'N/A', $dht11->temp_k ?? 'N/A', $mq2->gas_value, $raindrop->rain_value ?? 'N/A', $dht11->humid ?? 'N/A');
        }

        // Mendapatkan ID produk yang baru saja ditambahkan
        $mq2Id = $mq2->id;

        // Membuat pesan JSON yang menyertakan data produk yang baru saja ditambahkan
        return response()->json([
            "message" => "Data Sensor MQ-2 Berhasil Ditambahkan",
            "data" => [
                "id" => $mq2Id,
                "gas_value" => $mq2->gas_value
            ]
        ], 201);
    }

    // Fungsi untuk mengirim notifikasi WhatsApp menggunakan Fonnte
    private function sendWhatsAppNotification($temp_c, $temp_f, $temp_k, $gas_value, $rain_value, $humid){
        $cacheKey = 'send_whatsapp_notification';

        if (Cache::has($cacheKey)) {
            // Notifikasi baru-baru ini sudah dikirim, jadi tidak mengirim lagi
            return;
        }

        // Membuat pesan yang mencakup data dari semua sensor
        $rain_condition = $rain_value == true ? 'Rain' : 'Not Rain';
        $message = "🔥🔥🔥 *MENYALA ABANGKU* 🔥🔥🔥\n\n*Gas Concentration:* {$gas_value} ppm\n*Temperature:*\n*- Celcius:* {$temp_c}°C\n*- Farenheit:* {$temp_f}°F\n*- Kelvin:* {$temp_k}°K \n*Humidity:* {$humid}%\n*Rain Condition:* {$rain_condition}\n\nThe notification will appear again if conditions remain dangerous in the next 1 minutes.";

        // Kirim notifikasi jika tidak ada dalam cache
        $client = new Client();
        $apiKey = '';  // Ganti dengan API Key Anda
        $phoneNumber = '';  // Ganti dengan nomor WhatsApp yang dituju

        $response = $client->post('https://api.fonnte.com/send', [
            'headers' => [
                'Authorization' => $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'target' => $phoneNumber,
                'message' => $message,
                'countryCode' => '62'  // Kode negara untuk Indonesia
            ],
        ]);

        // Set cache untuk mencegah pengiriman notifikasi berikutnya dalam 1 menit
        Cache::put($cacheKey, true, 60);  // 60 detik

        return $response->getBody()->getContents();
    }
}
