<?php

namespace App\Services;

class JwtService
{
    protected $secret;
    protected $ttl;

    public function __construct()
    {
        $this->secret = config('app.jwt_secret');
        $this->ttl = config('app.jwt_ttl');
    }

    private function base64UrlEncode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private function base64UrlDecode($data)
    {
        return base64_decode(strtr($data, '-_', '+/'));
    }

    public function generateToken($user)
    {
        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);

        $payload = json_encode([
            'sub' => $user->id,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + env('JWT_TTL', 3600)
        ]);

        $base64Header = $this->base64UrlEncode($header);
        $base64Payload = $this->base64UrlEncode($payload);

        $signature = hash_hmac(
            'sha256',
            $base64Header . "." . $base64Payload,
            env('JWT_SECRET'),
            true
        );

        $base64Signature = $this->base64UrlEncode($signature);

        return $base64Header . "." . $base64Payload . "." . $base64Signature;
    }

    public function validateToken($token)
    {
        $parts = explode('.', $token);

        if (count($parts) != 3) {
            return null;
        }

        [$header, $payload, $signature] = $parts;

        $validSignature = $this->base64UrlEncode(
            hash_hmac(
                'sha256',
                $header . "." . $payload,
                env('JWT_SECRET'),
                true
            )
        );

        if (!hash_equals($validSignature, $signature)) {
            return null;
        }

        $payloadData = json_decode($this->base64UrlDecode($payload), true);

        if ($payloadData['exp'] < time()) {
            return null;
        }

        return $payloadData;
    }
}
