<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImageUpdateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\HttpsResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use HttpsResponse;

    public function index($id)
    {
        $user = new UserResource(User::find($id));
        return $this->success('Welcome, ' . $user->name, $user, []);
    }

    public function update(Request $request)
    {

        $user = $request->user();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'description' => $request->description,
        ]);

        return $this->success('Info has been updated with success', null, [], 204);
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
