<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Product\ProductHelper;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\Product\ProductResource;

class ProductController extends Controller
{
    private $product;
    public function __construct()
    {
        $this->product = new ProductHelper();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filter = [
            'name' => request()->name ?? '',
            'm_product_category_id' => request()->m_product_category_id ?? '',
            'is_available' => request()->is_available ?? '',
        ];
        $products = $this->product->getAll($filter, 10, request()->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => ProductResource::collection($products['data']),
            'meta' => [
                'links' => $products['links'],
                'total' => $products['total']
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }
        $payload = $request->only(['name', 'price', 'm_product_category_id', 'is_available', 'description', 'photo', 'detail']);
        $product = $this->product->create($payload);

        if (!$product['status']) {
            return response()->json([
                'success' => false,
                'message' => $product['message'] ?? 'Failed to create product'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product['data']),
            'message' => 'Product created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = $this->product->getById($id);

        if (!$product['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product['data'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $id)
    {
        if(isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }
        $payload = $request->only(['name', 'price', 'm_product_category_id', 'is_available', 'description', 'photo', 'detail']);
        $product = $this->product->update($payload, $id);

        if (!$product['status']) {
            return response()->json([
                'success' => false,
                'message' => $product['message'] ?? 'Failed to update product'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => new ProductResource($product['data']),
            'message' => 'Product updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = $this->product->delete($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => $product['message'] ?? 'Failed to delete product'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'meesage' => 'Product deleted successfully'
        ]);
    }
}
