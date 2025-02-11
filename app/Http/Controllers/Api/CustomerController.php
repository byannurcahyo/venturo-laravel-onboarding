<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Helpers\User\UserHelper;
use App\Http\Controllers\Controller;
use App\Helpers\Customer\CustomerHelper;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\Resource\CustomerResource;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    private $customer;
    private $user;

    public function __construct()
    {
        $this->customer = new CustomerHelper();
        $this->user = new UserHelper();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
        ];
        $customers = $this->customer->getAll($filter, 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => CustomerResource::collection($customers['data']),
            'meta' => [
                'links' => $customers['links'],
                'total' => $customers['total']
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CustomerRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }
        try {
            DB::beginTransaction();

            $payload_user = $request->only(['email', 'name', 'password']);
            $user = $this->user->create($payload_user);

            if (!$user['status'] || empty($user['data'])) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Failed to create user"
                ], 500);
            }

            $userId = is_object($user['data']) ? $user['data']->id : ($user['data']['id'] ?? null);

            if (!$userId) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "User ID is missing"
                ], 500);
            }

            $payload_customer = $request->only(['name', 'address', 'phone', 'photo']);
            $payload_customer['m_user_id'] = $userId;

            $customer = $this->customer->create($payload_customer);

            if (!$customer['status']) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => $customer['message'] ?? 'Failed to create customer'
                ], 500);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => new CustomerResource($customer['data']),
                'message' => 'Customer created successfully'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = $this->customer->getById($id);
        if (!$customer['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new CustomerResource($customer['data'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }

        $payload = $request->only([
            'name',
            'address',
            'photo',
            'phone',
        ]);
        $customer = $this->customer->update($payload, $id);

        if (!$customer['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update customer'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => new CustomerResource($customer['data']),
            'message' => 'Customer updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = $this->customer->delete($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully'
        ]);
    }
}
