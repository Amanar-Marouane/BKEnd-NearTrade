<?php

namespace App\Http\Controllers;

use App\Models\ChatIds;

class ChatIdsController extends Controller
{
    public static function findOrMake($user1, $user2)
    {
        $chatEntry = ChatIds::where(function ($query) use ($user1, $user2) {
            $query->where('user1', $user1)
                ->where('user2', $user2);
        })->orWhere(function ($query) use ($user1, $user2) {
            $query->where('user1', $user2)
                ->where('user2', $user1);
        })->first();

        if (!$chatEntry) {
            $users = [$user1, $user2];
            sort($users);

            $chatEntry = ChatIds::create([
                'user1' => $users[0],
                'user2' => $users[1],
            ]);
        }

        return $chatEntry->id;
    }
}
