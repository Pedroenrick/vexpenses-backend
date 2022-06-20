<?php

namespace App\Http\Controllers;

use App\Models\JwtAuth;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $token = auth('api')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 403);
        }

        return response()->json([
            'token' =>  $token,
        ]);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Logout successful'
        ]);
    }

    public function refresh(): String
    {
        $token = auth('api')->refresh();
        return response()->json([
            'token' => $token,
        ]);
    }

    public function me(): \Illuminate\Http\JsonResponse
    {
        return response()->json(auth()->user());
    }

    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate($this->user->rules());

        try {
            $this->user->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json([
                "message" => "User created successfully"
            ], 201);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
