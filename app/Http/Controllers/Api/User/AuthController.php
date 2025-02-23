<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends UserBasedController
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()
            ->create($request->validated());

        return response()
            ->json($user, Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()
            ->where('email', $request->login)
            ->orWhere('phone_number', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('sanctum_token');

        return response()->json([
            'token' => $token->plainTextToken,
        ]);
    }

    public function logout(): JsonResponse
    {
        $this->getUser()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }
}
