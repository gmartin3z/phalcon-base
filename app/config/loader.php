<?php

use Phalcon\Loader;

$loader = new Loader();

$loader->registerNamespaces([
    'App\Models'          => $config->application->modelsDir,
    'App\Controllers'     => $config->application->controllersDir,
    'App\Libraries'       => $config->application->librariesDir,
    'App\Validators'      => $config->application->validatorsDir
])->register();

require_once BASE_PATH . '/vendor/autoload.php';
