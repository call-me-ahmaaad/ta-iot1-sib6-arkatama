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
                'message' => 'Oops! It looks like you don\'t have access to this resource. Please provide a valid API Key.',
                'error_code' => 'API_KEY_INVALID'
            ], 401);
        }

        return $next($request);
    }
}
