<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Product\ProductCategoryHelper;
use App\Http\Requests\Product\CategoryRequest;
use App\Http\Resources\Product\CategoryResource;

class ProductCategoryController extends Controller
{
    private $category;
    public function __construct()
    {
        $this->category = new ProductCategoryHelper();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
        ];
        $categories = $this->category->getAll($filter, 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories['data']),
            'meta' => [
                'links' => $categories['links'],
                'total' => $categories['total']
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        if(isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }
        $payload = $request->only(['name']);
        $category = $this->category->create($payload);

        if (!$category['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create category'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category['data']),
            'message' => 'Category created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->category->getById($id);

        if (!$category['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category['data'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        if(isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }
        $payload = $request->only(['name']);
        $category = $this->category->update($payload, $id);

        if (!$category['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update category'
            ], 500);
        }
        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category['data']),
            'message' => 'Category updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = $this->category->delete($id);

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete category'
            ], 500);
        }
        return response()->json([
            'success' => true,
            'meesage' => 'Category deleted successfully'
        ]);
    }
}
