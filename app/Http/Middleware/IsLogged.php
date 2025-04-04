<?php

namespace App\Http\Middleware;

use App\Traits\HttpsResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class IsLogged
{
    use HttpsResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $access_token = $request->cookie('access_token');
        if ($access_token) {
            try {
                $user = JWTAuth::setToken($access_token)->authenticate();
                if ($user) return $this->error('Logout first. You are already logged in.', null, [], 403);
            } catch (JWTException $e) {
                return $next($request);
            }
        }
        return $next($request);
    }
}
