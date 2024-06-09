<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');
        $validApiKey = env('API_KEY');  // simpan API Key di file .env

        if ($apiKey !== $validApiKey) {
            return response()->json([
                'success' => false,
                'message' => '⚠️⚠️ Sorry, you do not have access to operate this resource. Gain access to continue operating this resource. ⚠️⚠️',
                'status' => '🚫🚫 ACCESS DENIED 🚫🚫'
            ], 401);
        }

        return $next($request);
    }
}
