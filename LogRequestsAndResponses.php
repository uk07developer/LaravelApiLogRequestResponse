<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class LogRequestsAndResponses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Capture request data
        $ip = $request->ip();
        $url = $request->fullUrl();
        $method = $request->method();
        $timestamp = now()->toDateTimeString();

        Log::channel('request_response')->info('********************************************************************************************', []);

        // Log request data
        Log::channel('request_response')->info("Request Log: ", [
            'timestamp' => $timestamp,
            'ip' => $ip,
            'method' => $method,
            'url' => $url,
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
        ]);

        Log::channel('request_response')->info('********************************************************************************************', []);

        // Get the response
        $response = $next($request);

        // Log response data
        Log::channel('request_response')->info("Response Log: ", [
            'timestamp' => $timestamp,
            'ip' => $ip,
            'method' => $method,
            'url' => $url,
            'status' => $response->status(),
            'response' => $response->getContent(),
        ]);

        return $response;
    }
}
