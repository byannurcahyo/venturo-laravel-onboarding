<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Helpers\Role\RoleHelper;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\Role\RoleResource;

class RoleController extends Controller
{
    private $role;

    public function __construct()
    {
        $this->role = new RoleHelper();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'access' => $request->access ?? '',
        ];
        $roles = $this->role->getAll($filter, 10, $request->sort ?? '');

        return response()->json([
            'success' => true,
            'data' => RoleResource::collection($roles['data']),
            'meta' => [
                'links' => $roles['links'],
                'total' => $roles['total']
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }

        $payload = $request->only([
            'name',
            'access',
        ]);
        $role = $this->role->create($payload);

        if (!$role['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create role'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => new RoleResource($role['data']),
            'message' => 'Role created successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = $this->role->getById($id);

        if (!$role['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Role not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new RoleResource($role['data'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }

        $payload = $request->only([
            'name',
            'access',
        ]);
        $role = $this->role->update($payload, $id);

        if (!$role['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update role'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => new RoleResource($role['data']),
            'message' => 'Role updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = $this->role->delete($id);

        if (!$role) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete role'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully'
        ]);
    }
}
