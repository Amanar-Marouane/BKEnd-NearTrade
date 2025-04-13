<?php

namespace App\Policies;

use App\Models\{User, Product};
use App\Traits\HttpsResponse;

class ProductPolicy
{
    use HttpsResponse;
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function delete(User $user, Product $product)
    {
        return $user->role === 'Admin' || $product->user_id === $user->id;
    }
}
