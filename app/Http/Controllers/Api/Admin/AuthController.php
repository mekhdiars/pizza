<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Requests\Admin\Auth\RegisterRequest;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends AdminBasedController
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $admin = Admin::query()
            ->create($request->validated());

        return response()
            ->json($admin, Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $admin = Admin::query()
            ->where('email', $request->login)
            ->orWhere('phone_number', $request->login)
            ->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $admin->createToken('admin_sanctum_token');

        return response()->json([
            'token' => $token->plainTextToken,
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->getAdmin()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
