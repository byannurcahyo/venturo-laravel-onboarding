<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\User\UserHelper;
use App\Http\Requests\UserRequest;
use App\Http\Resources\User\UserResource;

class UserController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new UserHelper();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? '',
            'email' => $request->email ?? '',
        ];
        $users = $this->user->getAll($filter, $request->page ?? 25 ,$request->sort ?? '');
        return response()->json([
            'success' => true,
            'list' => UserResource::collection($users['data']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }

        $payload = $request->only([
            'name',
            'email',
            'password',
            'photo',
            'm_user_roles_id'
        ]);
        $user = $this->user->create($payload);

        if (!$user['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create user'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($user['data'])
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->user->getById($id);

        if (!$user['status']) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($user['data'])
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $request->validator->errors()
            ], 400);
        }

        $payload = $request->only([
            'name',
            'email',
            'password',
            'photo',
            'm_user_roles_id'
        ]);
        $user = $this->user->update($payload, $id);

        if (!$user['status']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($user['data'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = $this->user->delete($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user'
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}

