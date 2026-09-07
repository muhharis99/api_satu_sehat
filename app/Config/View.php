<?php

namespace Config;

use CodeIgniter\Config\View as BaseView;
use CodeIgniter\View\ViewDecoratorInterface;

class View extends BaseView
{
    public $saveData = true;

    public $filters = [];

    public $plugins = [];

    /** @var list<class-string<ViewDecoratorInterface>> */
    public array $decorators = [];

    public string $appOverridesFolder = 'overrides';
}
