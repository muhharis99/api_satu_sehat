<?php

namespace Config;

use CodeIgniter\Modules\Modules as BaseModules;

/**
 * Modules Configuration.
 *
 * NOTE: This class is required prior to Autoloader instantiation,
 * so it must not extend BaseConfig.
 */
class Modules extends BaseModules
{
    public $enabled = true;

    public $discoverInComposer = true;

    public $composerPackages = [];

    public $aliases = [
        'events',
        'filters',
        'registrars',
        'routes',
        'services',
    ];
}
