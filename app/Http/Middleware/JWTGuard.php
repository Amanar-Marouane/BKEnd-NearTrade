<?php

namespace App\Http\Middleware;

use App\Models\User;
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
        $access_token = $request->cookie('access_token');
        $refresh_token = $request->cookie('refresh_token');
        if (!$access_token || !$refresh_token) {
            return $this->error('Tokens are missing. Login first.', null, [], 403);
        }

        $user = JWTAuth::setToken($access_token)->authenticate();
        if (!$user) {
            $user = User::where('refresh_token', $refresh_token)->first();
            if (!$user) return $this->error('Invalid or expired tokens.', null, [], 403);
        }

        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        return $next($request);
    }
}
