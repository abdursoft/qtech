<?php

namespace App\Http\Middleware;

use App\Helper\JWTAuth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Handle CORS preflight request early
        if ($request->getMethod() === "OPTIONS") {
            return response()->json([], 204);
        }

        if (empty($request->bearerToken())) {
            return response()->json([
                'status'  => false,
                'message' => 'Please provide your authorization token in the request header',
            ], 401);
        }

        $token = JWTAuth::verifyToken($request->bearerToken(), false);

        if ($token && $token->role === 'customer') {
            $request->headers->set('id', $token->id);
            $request->merge([
                'auth' => \App\Models\User::select('id', 'name', 'email','role')->find($token->id),
            ]);
            return $next($request);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Unauthorized',
        ], 401);
    }
}
