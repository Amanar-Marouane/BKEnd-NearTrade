<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StoreProductRequest};
use App\Http\Resources\{CategoryResource, ProductResource, UserResource};
use App\Models\{Category, Product};
use App\Traits\{HttpsResponse};
use Illuminate\Http\Request;
use Illuminate\Support\{Str};

use function PHPUnit\Framework\isEmpty;

class ProductController extends Controller
{
    use HttpsResponse;

    public function index(Request $request)
    {
        $products = Product::where('user_id', '!=', $request->user()->id)->get();
        return $this->success(null, ProductResource::collection($products));
    }

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

        $product->deleteMedia();
        $product->delete();
        return $this->success('Product Has Been Deleted With Success');
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $product = Product::find($id);

        if (!$product) return $this->error('Product NOT FOUND', null, [], 404);
        if ($user->cannot('update', $product)) {
            return $this->error('Access denied', null, [], 403);
        }

        $imagesPath = '|';
        foreach ($request->file('images') as $image) {
            $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('Products', $imageName, 'public');
            $imagePath = env('CURRET_HOST') . '/storage/Products/' . $imageName;
            $imagesPath .= $imagePath . '|';
        }

        $product->deleteMedia();

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'status' => $request->status,
            'price' => $request->price,
            'location' => $request->location,
            'images' => $imagesPath,
        ]);
        return $this->success('Product Has Been updated With Success', null, [], 204);
    }

    public function filter(Request $request)
    {
        $products = Product::where('name', 'LIKE', "%{$request->name}%")
            ->where('price', '>=', $request->price ?? 0)
            ->where('location', 'LIKE', "%{$request->location}%")
            ->where('category_id', 'LIKE', "%{$request->category_id}%");

        if ($request->filled('status') && is_array($request->status) && count($request->status)) {
            $products->whereIn('status', $request->status);
        }

        return $this->success(null, ProductResource::collection($products->get()));
    }
}
