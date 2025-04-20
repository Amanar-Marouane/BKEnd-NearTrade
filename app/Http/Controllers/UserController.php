<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUpdateRequest;
use App\Http\Resources\UserResource;
use App\Traits\HttpsResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use HttpsResponse;

    public function index(Request $request)
    {
        $user = new UserResource($request->user());
        return $this->success('Welcome, ' . $user->name, $user, []);
    }


    public function imageUpdating(ImageUpdateRequest $request)
    {
        $user = $request->user();
        $oldImagePath = $user->profile;

        $newImageFile = $request->file('profile');

        $newImageName = Str::uuid() . '.' . $newImageFile->getClientOriginalExtension();
        $newImageFile->storeAs('profiles', $newImageName, 'public');
        $user->update([
            'profile' => 'storage/profiles/' . $newImageName
        ]);

        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }

        return $this->success('Profile image has been updated successfully', null, [], 204);
    }
}
