<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\RegisterUserRequest;
use App\Http\Resources\User\UserResource;
use App\Mail\RegisterEmail;
use App\Mail\TestEmail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthenticateController extends Controller
{
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        $token = $user->createToken('auth_token')->plainTextToken;

        try {
            Mail::to($user->email)->send(new RegisterEmail($user));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        }

        return response()->json([
            "user" => new UserResource($user),
            "token" => $token
        ], 201);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        if (!Auth::attempt($request->validated())) {
            return response()->json([
                "message" => "Invalid login credentials"
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        auth()->user()->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }
}
