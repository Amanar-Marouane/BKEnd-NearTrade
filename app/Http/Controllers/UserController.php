<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Traits\HttpsResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use HttpsResponse;

    public function index(Request $request)
    {
        $user = new UserResource($request->user());
        return $this->success('Welcome, ' . $user->name, $user, []);
    }
}
