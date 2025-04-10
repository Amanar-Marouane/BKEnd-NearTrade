<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreProductRequest};
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
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
            'user_id' => $request->user_id,
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

    public function index(Request $request)
    {
        $products = Product::where('user_id', $request->user()->id)->get();
        return $this->success(null, ProductResource::collection($products));
    }

    public function add()
    {
        return $this->success(null, [
            'categories' => CategoryResource::collection(Category::all()),
            'status' => ['New', 'Used'],
        ]);
    }
}
