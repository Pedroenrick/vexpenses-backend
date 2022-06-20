<?php

namespace App\Models;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Hash;

abstract class JwtAuth
{
    public static function generateToken(User $user): String
    {
        $secret = env('TOKEN_KEY');
        $token = JWT::encode(
            [
                'email' => $user->name,
                'password' => $user->email,
                'iat' => time(),
                'exp' => time() + (3600 * 3)
            ],
            $secret,
            'HS256'
        );
        return $token;
    }

    public static function decodeToken(String $token)
    {   
        $secret = env('TOKEN_KEY');
        $decoded = JWT::decode($token, new Key($secret, 'HS256'));

        return $decoded;
    }

    public static function attempt(array $credentials): ?User
    {
        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return null;
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return null;
        }

        return $user;
    }

    // public static function getUserFromToken(String $token): ?User
    // {
    //     $decoded = self::decodeToken($token);

    //     dd($decoded);
    //     if (!$decoded) {
    //         return null;
    //     }

    //     return User::where('email', $decoded->email)->first();
    // }

    // public static function refresh(String $token): String
    // {
    //     $decoded = self::decodeToken($token);

    //     $token = generateToken($decoded->email);

    //     return $token;
    // }
}
