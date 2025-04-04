<?php

namespace App\Http\Controllers;

use App\Http\Requests\{UserLoginRequest, UserStoreRequest};
use App\Http\Resources\{UserResource};
use App\Traits\{HttpsResponse};
use Illuminate\Http\{Request};
use App\Models\{User};
use Illuminate\Support\{Str};
use Illuminate\Support\Facades\{Auth, Cookie, Hash};
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\{JWTAuth};

class AuthController extends Controller
{
    use HttpsResponse;

    private function jwtGenerator(User $user)
    {
        $access_token = JWTAuth::fromUser($user);
        $refresh_token = hash('sha256', Str::random(60));
        $user->update(['refresh_token' => Hash::make($refresh_token)]);

        return [
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
        ];
    }

    public function store(UserStoreRequest $request)
    {
        // if (!$request->hasFile('profile')) return $this->error('Error occured while uploading image', null, [], 400);
        // $image = $request->file('profile');
        // $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
        // $image->storeAs('/profiles', $imageName);
        // $imagePath = '/profiles/' . $imageName;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile' => $imagePath ?? '/profile.jpg',
        ]);
        Auth::login($user);
        $cookies = $this->jwtGenerator($user);
        return $this->success('Account created succefully', new UserResource($user), $cookies, 201);
    }

    public function login(UserLoginRequest $request)
    {
        if (!Auth::attempt(
            ['email' => $request->email, 'password' => $request->password]
        )) return $this->error('Invalid credentials', null, [], 401);

        $user = Auth::user();
        $cookies = $this->jwtGenerator($user);
        return $this->success('Account logged succefully', new UserResource($user), $cookies);
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        $user->update([
            'refresh_token' => null,
        ]);
        $access_token = $user->getJWTIdentifier();

        JWTAuth::invalidate($access_token);
        Auth::logout();

        return $this->success('Account logout succefully', null, [
            'access_token' => '',
            'refresh_token' => '',
        ]);
    }

    public function isLogged(Request $request)
    {
        if ($access_token = $request->cookie('access_token')) {
            try {
                $user = JWTAuth::setToken($access_token)->authenticate();
                if ($user) return $this->success('Signup or login first', false, []);
            } catch (JWTException $e) {
                return $this->success('You can proceed', true, []);
            }
        }
        return $this->success('You can proceed', true, []);
    }
}
