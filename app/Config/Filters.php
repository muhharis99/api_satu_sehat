<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;

class Filters extends BaseFilters
{
    /**
     * Configures global filters that run before and/or after every request.
     */
    public array $globals = [
        'before' => [],
        'after'  => [
            'toolbar',
        ],
    ];

    /**
     * Method-specific filters.
     */
    public array $methods = [];

    /**
     * URI-pattern specific filters.
     */
    public array $filters = [];
}
