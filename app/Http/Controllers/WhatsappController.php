<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappController extends Controller
{
    public function sendWhatsapp(Request $request){
        $message = $request->input('message');
        $apiKey = env('FONTE_API_KEY');
        $phoneNumber = '6282299006083'; // Recipient's phone number in international format

        $response = Http::post('https://api.fonte.com/send-message', [
            'api_key' => $apiKey,
            'to' => $phoneNumber,
            'message' => $message,
        ]);

        if ($response->successful()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => $response->body()], $response->status());
        }
    }
}
