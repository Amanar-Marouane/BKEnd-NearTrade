<?php

namespace App\Http\Middleware;

use App\Traits\HttpsResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JWTGuard
{
    use HttpsResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$access_token = $request->cookie('access_token')) {
            return $this->error('Access token is missing. Login first.', null, [], 403);
        }

        try {
            $user = JWTAuth::setToken($access_token)->authenticate();

            $request->setUserResolver(function () use ($user) {
                return $user;
            });
        } catch (JWTException $e) {
            return $this->error('Invalid or expired token.', null, [], 401);
        }

        return $next($request);
    }
}
