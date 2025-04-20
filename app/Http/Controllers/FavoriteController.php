<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Favorite;
use App\Models\FavoriteModel;
use App\Models\Product;
use App\Traits\HttpsResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    use HttpsResponse;

    public function index(Request $request)
    {
        $user = $request->user();
        return $this->success(null, ProductResource::collection($user->favorites));
    }

    private function productCheck($product_id)
    {
        $product = Product::find($product_id);

        if (!$product) {
            return $this->error('Entry Not Found', null, [], 404);
        }

        return $product;
    }

    private function fav($user_id, Product $product)
    {
        $fav = Favorite::create([
            'user_id' => $user_id,
            'product_id' => $product->id,
        ]);
        if (!$fav) return $this->error('Something went wrong', null, [], 400);
        return $this->success(null, ['favorites_count' => $product->favorites_count]);
    }

    private function unFav(Favorite $fav, Product $product)
    {
        $fav->delete();
        return $this->success(null, ['favorites_count' => $product->favorites_count]);
    }

    public function favManager(Request $request, $id)
    {
        $product = $this->productCheck($id);
        if (!$product instanceof Product) {
            return $product;
        }

        $user = $request->user();

        $fav = Favorite::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if (!$fav) return $this->fav($user->id, $product);
        else return $this->unFav($fav, $product);
    }
}
