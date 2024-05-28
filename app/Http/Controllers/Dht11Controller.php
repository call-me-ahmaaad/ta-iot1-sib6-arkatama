<?php

namespace App\Http\Controllers;

use App\Models\Dht11;
use App\Models\Raindrop;

use Illuminate\Http\Request;

class Dht11Controller extends Controller
{




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
