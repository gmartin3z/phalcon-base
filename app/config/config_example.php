<?php

defined('BASE_PATH') || define('BASE_PATH', getenv('BASE_PATH') ?: realpath(dirname(__FILE__) . '/../..'));
defined('APP_PATH') || define('APP_PATH', BASE_PATH . '/app');

use Phalcon\Config;
use Phalcon\Logger;

$now = strval(date('d-m-Y'));

return new Config([
    'database' => [
        'adapter'     => 'MYSQL',
        'host'        => '127.0.0.1',
        'port'        => '3306',
        'username'    => 'us3r',
        'password'    => 'p@ss',
        'dbname'      => 'phalcon_base_app',
        'charset'     => 'utf8mb4',
    ],
    'application' => [
        'appDir'          => APP_PATH . '/',
        'controllersDir'  => APP_PATH . '/controllers/',
        'modelsDir'       => APP_PATH . '/models/',
        'viewsDir'        => APP_PATH . '/views/',
        'validatorsDir'   => APP_PATH . '/validators/',
        'librariesDir'    => APP_PATH . '/libraries/',
        'langsDir'        => APP_PATH . '/messages/',
        'cacheDir'        => BASE_PATH . '/cache/',
        'publicUrl'       => 'http://127.0.0.1',
        'baseUri'         => '/phalcon_base/public/',
        'cryptSalt'       => '',
        'defaultTimezone' => 'America/Mexico_City',
        'allowSignup'     => false
    ],
    'logger' => [
        'logsDir'  => BASE_PATH . '/logs/',
        'format'   => '%date% [%type%] %message%',
        'date'     => 'Y-m-d (h:i:s A)',
        'logLevel' => Logger::DEBUG,
        'filename' => 'log_' . $now . '.log'
    ],
    'mail' => [
        'useMail' => false,
        'fromName' => 'PHALCON-BASE',
        'fromEmail' => 'phalconbase@email.app',
        'smtp' => [
            'server' => '127.0.0.1',
            'port' => 25,
            'security' => '',
            'username' => 'phalconbase@email.app',
            'password' => 'ph@Lc0n_B4s3'
        ]
    ],
    'status' => [
        'maintenance' => false,
        'debuggable'  => false
    ]
]);
