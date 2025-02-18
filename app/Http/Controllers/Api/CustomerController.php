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
            $payload_user = $request->only(['email', 'name', 'password', 'phone_number']);
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
        try {
            DB::beginTransaction();
            $customerData = $this->customer->getById($id);
            if (!$customerData['status'] || empty($customerData['data'])) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }
            $customer = $customerData['data'];
            $userId = is_object($customer) ? $customer->m_user_id : ($customer['m_user_id'] ?? null);
            if (!$userId) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is missing'
                ], 500);
            }
            $payload_user = $request->only(['email', 'name', 'password']);
            if (!empty($payload_user)) {
                $userUpdate = $this->user->update($payload_user, $userId);
                if (!$userUpdate['status']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to update user'
                    ], 500);
                }
            }
            $payload_customer = $request->only(['name', 'address', 'phone', 'photo']);
            if (!empty($payload_customer)) {
                $customerUpdate = $this->customer->update($payload_customer, $id);
                if (!$customerUpdate['status']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to update customer'
                    ], 500);
                }
            }
            DB::commit();
            $updatedCustomer = $this->customer->getById($id);
            return response()->json([
                'success' => true,
                'data' => new CustomerResource($updatedCustomer['data']),
                'message' => 'Customer updated successfully'
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            $customerData = $this->customer->getById($id);
            if (!$customerData['status'] || empty($customerData['data'])) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Customer not found'
                ], 404);
            }
            $customer = $customerData['data'];
            $userId = is_object($customer) ? $customer->m_user_id : ($customer['m_user_id'] ?? null);
            if (!$userId) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'User ID is missing'
                ], 500);
            }
            $customerDelete = $this->customer->delete($id);
            if (!$customerDelete) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete customer'
                ], 500);
            }
            $userDelete = $this->user->delete($userId);
            if (!$userDelete) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete user'
                ], 500);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Customer and user deleted successfully'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
