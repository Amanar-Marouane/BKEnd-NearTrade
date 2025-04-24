<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use App\Traits\HttpsResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use HttpsResponse;

    public function store(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) return $this->error('Product not found', null, [], 404);

        $review = Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return $this->success('Review has been submited with success', new ReviewResource($review));
    }
}
