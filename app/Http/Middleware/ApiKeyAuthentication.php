<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');
        $validApiKey = config('app.api_key');

        if (!$apiKey || $apiKey !== $validApiKey) {
            return response()->json([
                'message' => 'Unauthorized. API Key non valida.'
            ], 401);
        }

        return $next($request);
    }
}
