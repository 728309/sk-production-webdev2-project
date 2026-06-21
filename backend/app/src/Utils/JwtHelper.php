<?php

namespace App\Utils;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtHelper
{
    private const SECRET = 'sk-production-dev-secret';
    private const ALGORITHM = 'HS256';
    private const TOKEN_LIFETIME_SECONDS = 86400;

    public static function createToken(User $user): string
    {
        $payload = [
            'userId' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'exp' => time() + self::TOKEN_LIFETIME_SECONDS,
        ];

        return JWT::encode($payload, self::getSigningKey(), self::ALGORITHM);
    }

    public static function validateToken(string $token): ?object
    {
        try {
            return JWT::decode($token, new Key(self::getSigningKey(), self::ALGORITHM));
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function getSigningKey(): string
    {
        return hash('sha256', self::SECRET);
    }
}
