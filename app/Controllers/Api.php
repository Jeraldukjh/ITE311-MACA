<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;

class Api extends BaseController
{
    use ResponseTrait;

    /**
     * Get a new CSRF token
     */
    public function csrf()
    {
        return $this->respond([
            'csrf' => csrf_hash()
        ]);
    }
}
