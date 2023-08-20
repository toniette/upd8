<?php

namespace App\Http\Controllers\Api\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationController extends Controller
{
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->only(['email' , 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();

        $user->tokens()->delete();

        $token = $user->createToken($user->id)->plainTextToken;

        return response()->json([
            'message' => 'Authenticated',
            'user' => $user,
            'token' => $token
        ]);
    }
}
