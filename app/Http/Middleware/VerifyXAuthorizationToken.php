<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;

class VerifyXAuthorizationToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('X-Authorization');

        $apiKey = ApiKey::where('key', $token)->where('active', 1)->first();

        if ($apiKey) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
    }
}
