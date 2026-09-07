<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Debug\ExceptionHandler;
use CodeIgniter\Debug\ExceptionHandlerInterface;
use Psr\Log\LogLevel;
use Throwable;

/**
 * Setup how the exception handler works.
 */
class Exceptions extends BaseConfig
{
    public bool $log = true;

    /** @var list<int> */
    public array $ignoreCodes = [404];

    /**
     * Path to the directory containing the cli and html error views.
     */
    public string $errorViewPath = APPPATH . 'Views/errors';

    /** @var list<string> */
    public array $sensitiveDataInTrace = [];

    public bool $logDeprecations = true;

    public string $deprecationLogLevel = LogLevel::WARNING;

    /**
     * Return the exception handler used by CodeIgniter.
     */
    public function handler(int $statusCode, Throwable $exception): ExceptionHandlerInterface
    {
        return new ExceptionHandler($this);
    }
}
