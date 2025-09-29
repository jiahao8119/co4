<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!function_exists('generate_jwt')) {
    function generate_jwt($payload)
    {
        $secret = getenv('JWT_SECRET') ?: 'supersecretkey'; 
        $issuedAt   = time();
        $expire     = $issuedAt + 3600;

        $payload = array_merge($payload, [
            'iat' => $issuedAt,
            'exp' => $expire,
        ]);

        return JWT::encode($payload, $secret, 'HS256');
    }
}

if (!function_exists('decode_jwt')) {
    function decode_jwt($jwt)
    {
        $secret = getenv('JWT_SECRET') ?: 'supersecretkey';
        return JWT::decode($jwt, new Key($secret, 'HS256'));
    }
}
