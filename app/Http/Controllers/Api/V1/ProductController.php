<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Product\StoreProductRequest;
use App\Http\Requests\Api\V1\Product\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\HttpCache\Store;

class ProductController
{
    //

    // public function index(){
    //     $products = Product::latest()->get();
    //     return ProductResource::collection($products);
    // }

    // public function show($id){
    //     $product = Product::findOrFail($id);
    //     return new ProductResource($product);
    // }


    public function index()
    {
        $products = Product::with(['images', 'category'])
            ->latest()
            ->paginate(10);

        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::with(['images', 'category'])
            ->where('id', $id)
            ->firstOrFail();

        return new ProductResource($product);
    }
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        // Create product (slug auto-generated in model)
        $product = Product::create($data);
        foreach ($data['images'] as $image) {
            ProductImage::create([
                'product_id' => $product->id,
                'url' => $image,
            ]);
        }
        return response()->json([
            'data' => new ProductResource(
                $product->load(['images', 'category'])
            )
        ], 201);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update($request->validated());

        // Optional: update images
        if ($request->has('images')) {
            $product->images()->delete();

            foreach ($request->images as $image) {
                $product->images()->create([
                    'url' => $image,
                ]);
            }
        }

        return response()->json([
            'data' => new ProductResource(
                $product->load(['images', 'category'])
            )
        ]);
    }
}
