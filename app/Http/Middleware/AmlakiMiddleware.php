<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AmlakiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */

    private $jwt;

    public function __construct(JwtService $jwt)
    {
        $this->jwt = $jwt;
    }

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

        if (!$user || !$user->verifiedAt) {
            return response()->json(['message'=>'User not found'],401);
        }

        $request->auth = $user;

        return $next($request);
    }
}
