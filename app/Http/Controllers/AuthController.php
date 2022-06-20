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

        $user = JwtAuth::attempt($credentials);

        if (!$user) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 403);
        }

        $token = JwtAuth::generateToken($user);

        return response()->json([
            'message' => 'Login successful ' . $token,
        ]);
    }

    public function logout()
    {
        //
    }

    public function refresh()
    {
        //
    }

    public function me(Request $request)
    {
        // $token = JwtAuth::getUserFromToken($request->header('Authorization'));
        // dd($token);
        // dd(JwtAuth::decodeToken());
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
