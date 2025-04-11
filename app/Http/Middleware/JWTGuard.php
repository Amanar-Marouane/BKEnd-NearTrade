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

        try {
            $user = JWTAuth::setToken($access_token)->authenticate();

            if (!$user) {
                return $this->error('Invalid access token.', null, [], 403);
            }

            $request->setUserResolver(fn() => $user);

            return $next($request);
        } catch (JWTException $e) {
            $user = User::where('refresh_token', '=', $refresh_token)->first();

            if (!$user) {
                return $this->error('Invalid or expired tokens.', null, [], 403);
            }

            $new_access_token = JWTAuth::fromUser($user);
            $new_access_cookie = cookie('access_token', $new_access_token, 1440, '/', null, true, true, false, 'None');

            $request->setUserResolver(fn() => $user);

            return response($next($request))->withCookie($new_access_cookie);
        }
    }
}
