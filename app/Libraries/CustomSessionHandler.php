<?php

namespace App\Libraries;

use CodeIgniter\Session\Handlers\FileHandler;
use Config\Session as SessionConfig;

class CustomSessionHandler extends FileHandler
{
    public function __construct($config, string $ipAddress)
    {
        if ($config instanceof SessionConfig) {
            $savePath = $config->savePath;
            $name = $config->cookieName;
            
            // Ensure the session directory exists and is writable
            if (!is_dir($savePath)) {
                mkdir($savePath, 0700, true);
            }
            
            parent::__construct($config, $ipAddress);
        } else {
            // Fallback to parent constructor if not a SessionConfig object
            parent::__construct($config, $ipAddress);
        }
    }
}
