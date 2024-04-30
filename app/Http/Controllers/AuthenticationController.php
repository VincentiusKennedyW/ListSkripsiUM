<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserMeResource;
use App\Http\Resources\UserRegisterResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => new UserRegisterResource($user),
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'error' => true,
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        return response()->json([
            'error' => false,
            'message' => 'success',
            'token' => $user->createToken('token-user')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'error' => false,
            'message' => 'success',
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'error' => false,
            'message' => 'success',
            'data' => new UserMeResource($user),
        ]);
    }
}
