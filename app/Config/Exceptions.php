<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Debug\ExceptionHandlerInterface;
use CodeIgniter\Debug\Exceptions as CoreExceptions;

class Exceptions extends BaseConfig
{
    /**
     * --------------------------------------------------------------------------
     * LOG EXCEPTIONS?
     * --------------------------------------------------------------------------
     */
    public bool $log = true;

    /**
     * --------------------------------------------------------------------------
     * Error View Paths
     * --------------------------------------------------------------------------
     */
    public array $errorViewPaths = [
        APPPATH . 'Views/errors',
    ];

    /**
     * --------------------------------------------------------------------------
     * Exception Handler
     * --------------------------------------------------------------------------
     */
    public string $handler = CoreExceptions::class;
}
