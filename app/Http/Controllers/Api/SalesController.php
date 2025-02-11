<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Helpers\Sales\SalesHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Sales\SalesRequest;
use App\Http\Resources\Sales\SalesResource;

class SalesController extends Controller
{
    private $sales;
    public function __construct()
    {
        $this->sales = new SalesHelper();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filter = [
            'm_customer_id' => request()->m_customer_id ?? '',
            'date' => request()->date ?? '',
        ];
        $sales = $this->sales->getAll($filter, 5, request()->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => SalesResource::collection($sales['data']),
            'meta' => [
                'links' => $sales['links'],
                'total' => $sales['total']
            ]
        ]);
    }

    public function getSalesByCustomer(SalesRequest $request)
    {
        $filter = [
            'm_customer_id' => $request->customer_id ?? '',
            'date_from' => $request->date_from ?? '',
            'date_to' => $request->date_to ?? '',
        ];
        $sales = $this->sales->getSalesByCustomer($filter, 25, request()->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => $sales['data'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalesRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }
        $payload = $request->only(['m_customer_id', 'date', 'product_detail']);
        $sales = $this->sales->create($payload);

        if (!$sales['status']) {
            return response()->json([
                'success' => false,
                'message' => $sales['message'] ?? 'Failed to create sales'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => new SalesResource($sales['data']),
            'message' => 'Sales created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sales = $this->sales->getById($id);

        if (!$sales['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Sales not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => new SalesResource($sales['data'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SalesRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }
        $payload = $request->only(['m_customer_id', 'date', 'product_detail']);
        $sales = $this->sales->update($payload, $id);

        if (!$sales['status']) {
            return response()->json([
                'success' => false,
                'message' => $sales['message'] ?? 'Failed to update sales'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => new SalesResource($sales['data']),
            'message' => 'Sales updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sales = $this->sales->delete($id);

        if (!$sales['status']) {
            return response()->json([
                'success' => false,
                'message' => $sales['message'] ?? 'Failed to delete sales'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sales deleted successfully'
        ]);
    }

}
