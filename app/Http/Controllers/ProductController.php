<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreProductRequest};
use App\Http\Resources\{CategoryResource, ProductResource, UserResource};
use App\Models\{Category, Product, User};
use App\Traits\{HttpsResponse};
use Illuminate\Http\Request;
use Illuminate\Support\{Str};

class ProductController extends Controller
{
    use HttpsResponse;
    public function store(StoreProductRequest $request)
    {
        $images = $request->file('images');
        $imagesPath = '|';
        foreach ($images as $image) {
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('Products', $imageName, 'public');
            $imagePath = env('CURRET_HOST') . '/storage/Products/' . $imageName;
            $imagesPath .= $imagePath . '|';
        }

        $product = Product::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
            'price' => $request->price,
            'location' => $request->location,
            'images' => $imagesPath,
        ]);

        return $this->success('Product has been created with success', $product, [], 201);
    }

    public function userProducts(Request $request)
    {
        $products = $request->user()->products;
        return $this->success(null, ProductResource::collection($products));
    }

    public function add()
    {
        return $this->success(null, [
            'categories' => CategoryResource::collection(Category::all()),
            'status' => ['New', 'Used'],
        ]);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $product = Product::find($id);
        if ($product) return $this->success('Product fetched with success', [
            'product' => new ProductResource($product),
            'user' => new UserResource($user),
        ]);
        return $this->error('Product NOT FOUND', null, [], 404);
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        $product = Product::find($id);

        if (!$product) return $this->error('Product NOT FOUND', null, [], 404);

        if ($user->cannot('delete', $product)) {
            return $this->error('Access denied', null, [], 403);
        }
        $product->delete();
        return $this->success('Product Has Been Deleted With Success');
    }
}
