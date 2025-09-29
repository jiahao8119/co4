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
        $username = $request->getPost('username');
        $password = $request->getPost('password');

        // Dummy check (replace with DB later)
        if ($username === 'admin' && $password === '1234') {
            $secret = getenv('JWT_SECRET') ?: 'supersecretkey';
            $payload = [
                'iss' => "ci4-jwt",     // Issuer
                'sub' => $username,     // Subject
                'iat' => time(),        // Issued at
                'exp' => time() + 3600  // Expire time (1 hour)
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
