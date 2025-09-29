<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Auth extends Controller
{
    public function login()
    {
        $request = service('request');
        $data = $request->getJSON(true); // true = return as array

        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if ($username === 'admin' && $password === '1234') {
            $secret = getenv('JWT_SECRET') ?: 'supersecretkey';
            $payload = [
                'iss' => "ci4-jwt",
                'sub' => $username,
                'iat' => time(),
                'exp' => time() + 3600
            ];

            $jwt = JWT::encode($payload, $secret, 'HS256');

            return $this->response->setJSON([
                'status' => 200,
                'token' => $jwt
            ]);
        }

        return $this->response->setJSON([
            'status' => 401,
            'message' => 'Invalid login'
        ]);
    }


    public function profile()
    {
        $authHeader = $this->request->getHeaderLine("Authorization");
        if (!$authHeader) {
            return $this->response->setJSON([
                'status' => 401,
                'message' => 'Token required'
            ]);
        }

        $token = str_replace("Bearer ", "", $authHeader);
        try {
            $secret = getenv('JWT_SECRET') ?: 'supersecretkey';
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));

            return $this->response->setJSON([
                'status' => 200,
                'user' => $decoded->sub
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 401,
                'message' => 'Invalid token: ' . $e->getMessage()
            ]);
        }
    }
}
