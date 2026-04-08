<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Category\StoreCategoryRequest;
use App\Http\Resources\Api\V1\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController
{
    //
      public function index()
    {
        $categories = Category::latest()->get();

        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json([
            'data' => new CategoryResource($category)
        ], 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);

        return new CategoryResource($category);
    }

    public function update(StoreCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->update($request->validated());

        return response()->json([
            'data' => new CategoryResource($category)
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' => 'Category deleted'
        ]);
    }
}
