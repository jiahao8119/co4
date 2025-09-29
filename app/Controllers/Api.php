<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController
{
    public function hello()
    {
        return $this->respond([
            'status'  => 200,
            'message' => 'Hello from CodeIgniter API',
        ]);
    }
}
