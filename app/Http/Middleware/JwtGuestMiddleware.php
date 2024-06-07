<?php

namespace App\Http\Middleware;

use App\Http\Resources\Jwt\AuthResource;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Token;

class JwtGuestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return $this->returnErrorResponse('Token não fornecido');
        }

        try {
            $jwtToken = new Token($token);
            JWTAuth::decode($jwtToken);
        } catch (JWTException $e) {
            return $this->returnErrorResponse('Token de autenticação está incorreto');
        }

        return $next($request);
    }

    private function returnErrorResponse($message){
        return response()->json(new AuthResource(['status' => false, 'message' => $message]), Response::HTTP_UNAUTHORIZED);
    }
}
