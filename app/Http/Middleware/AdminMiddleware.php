<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader) {
            return response()->json(['message'=>'Token missing'],401);
        }

        $token = str_replace('Bearer ', '', $authHeader);

        $payload = $this->jwt->validateToken($token);

        if (!$payload) {
            return response()->json(['message'=>'Invalid token'],401);
        }

        $user = User::find($payload['sub']);

        if (!$user || $user-> type != 'admin') {
            return response()->json(['message'=>'User not found'],401);
        }

        return $next($request);    }
}
