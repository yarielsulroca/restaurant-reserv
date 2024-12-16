<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:admin')->except(['updatePassword']);
    }

    public function index(): JsonResponse
    {
        $users = User::with('roles')->get();
        return response()->json([
            'users' => UserResource::collection($users)
        ]);
    }

    public function show(User $user): JsonResponse
    {
        $user->load('roles');
        return response()->json([
            'user' => new UserResource($user)
        ]);
    }

    public function updateRole(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:admin,user']
        ]);

        $user->syncRoles([$validated['role']]);
        $user->load('roles');

        return response()->json([
            'message' => 'User role updated successfully',
            'user' => new UserResource($user)
        ]);
    }

    public function updateStatus(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'is_active' => ['required', 'boolean']
        ]);

        $user->update(['is_active' => $validated['is_active']]);

        return response()->json([
            'message' => 'User status updated successfully',
            'user' => new UserResource($user)
        ]);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }

    public function adminUpdatePassword(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        return response()->json([
            'message' => 'User password updated successfully'
        ]);
    }
}
