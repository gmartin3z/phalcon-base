<?php

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Php as PhpEngine;
use Phalcon\Mvc\Url as UrlResolver;
use Phalcon\Filter;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaDataAdapter;
use Phalcon\Session\Factory as SessionFactory;
use Phalcon\Crypt as CryptManager;
use Phalcon\Security as SecurityManager;
use Phalcon\Http\Response\Cookies as CookiesManager;
use Phalcon\Flash\Session as Flash;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Logger\Adapter\File as FileLogger;
use Phalcon\Logger\Formatter\Line as FormatterLine;
use App\Libraries\Auth\AdminAuth;
use App\Libraries\ACL\Permissions;
use App\Libraries\Mailer\Mailer;
use App\Libraries\Translator\Translator;
use Moment\Moment;
use HtmlSanitizer\Sanitizer as HtmlSanitizer;

$di->setShared('config', function () {
    return include APP_PATH . '/config/config.php';
});

$di->setShared('url', function () {
    $config = $this->getConfig();

    $url = new UrlResolver();
    $url->setBaseUri($config->application->baseUri);

    return $url;
});

$di->setShared('filter', function() {
    $filter = new Filter();

    $filter->add(
        'clearInput',
        function ($dirty_text) {
            $sanitizer = HtmlSanitizer::create([
                'extensions' => ['basic', 'list'],
                'tags' => [
                    'div' => [
                        'allowed_attributes' => ['id', 'class'],
                    ],
                    'img' => [
                        'allowed_attributes' => ['src', 'alt', 'title', 'class'],
                    ],
                    'i' => [
                        'allowed_attributes' => ['class'],
                    ],
                    'span' => [
                        'allowed_attributes' => ['class'],
                    ],
                    'p' => [
                        'allowed_attributes' => ['class'],
                    ],
                    'ul' => [
                        'allowed_attributes' => ['class'],
                    ],
                    'li' => [
                        'allowed_attributes' => ['class'],
                    ],
                ],
            ]);

            $clean_text = $sanitizer->sanitize($dirty_text);
            return $clean_text;
        }
    );

    return $filter;
});

$di->set('translator', function () {
    return new Translator();
});

$di->setShared('view', function () {
    $config = $this->getConfig();

    $view = new View();
    $view->setDI($this);
    $view->setViewsDir($config->application->viewsDir);

    $view->registerEngines([
        '.volt.php' => function ($view) {
            $config = $this->getConfig();

            $volt = new VoltEngine($view, $this);

            $volt->setOptions([
                'compiledPath' => $config->application->cacheDir . 'volt/',
                'compiledSeparator' => '_',
                'compiledExtension' => '.tpl',
                'compileAlways' => true
            ]);

            $compiler = $volt->getCompiler();

            $default_timezone = $config->application->defaultTimezone;
            Moment::setDefaultTimezone($default_timezone);

            $compiler->addFunction('dateForHumans', 'viewDateForHumans');
            function viewDateForHumans($timestamp, $lang) {
                Moment::setLocale($lang);
                $m = new Moment($timestamp);
                return $m->calendar(false);
            }

            $compiler->addFunction('formatDate', 'viewFormatDate');
            function viewFormatDate($date) {
                $dc = date_create($date);
                $df = date_format($dc, 'd-m-Y (h:i A)');
                return $df;
            }

            $compiler->addFunction('addExtraDay', 'viewAddExtraDay');
            function viewAddExtraDay($timestamp, $lang) {
                Moment::setLocale($lang);
                $m = new Moment($timestamp);
                $m->addDays(1);
                return $m->calendar(false);
            }

            $compiler->addFunction('extractHour', 'viewExtractHour');
            function viewExtractHour($date) {
                $dc = date_create($date);
                $df = date_format($dc, '(h:i A)');
                return $df;
            }

            $compiler->addFunction('randomInt', 'viewRandomInt');
            function viewRandomInt($num1, $num2) {
                return random_int($num1, $num2);
            }

            $compiler->addFunction('parseMarkdown', 'viewParseMarkdown');
            function viewParseMarkdown($text) {
                $parse_md = new ParsedownExtra();
                $dirty_md = $parse_md->text($text);

                $sanitizer = HtmlSanitizer::create([
                    'extensions' => ['basic', 'list'],
                    'tags' => [
                        'div' => [
                            'allowed_attributes' => ['id', 'class'],
                        ],
                        'img' => [
                            'allowed_attributes' => ['src', 'alt', 'title', 'class'],
                        ],
                        'i' => [
                            'allowed_attributes' => ['class'],
                        ],
                        'span' => [
                            'allowed_attributes' => ['class'],
                        ],
                        'p' => [
                            'allowed_attributes' => ['class'],
                        ],
                        'ul' => [
                            'allowed_attributes' => ['class'],
                        ],
                        'li' => [
                            'allowed_attributes' => ['class'],
                        ],
                    ],
                ]);

                $clean_md = $sanitizer->sanitize($dirty_md);
                return $clean_md;
            }

            $compiler->addFunction('tr', 'viewTranslate');
            function viewTranslate($text, $arr = []) {
                $tr = new Translator();
                $str = $tr->getTranslation();
                return $str->_($text, $arr);
            }

            $compiler->addFunction('count', 'viewCount');
            function viewCount($resultset) {
                return count($resultset);
            }

            return $volt;
        }
    ]);

    return $view;
});

$di->setShared('db', function () {
    $config = $this->getConfig();

    $class = 'Phalcon\Db\Adapter\Pdo\\' . $config->database->adapter;
    $params = [
        'host'     => $config->database->host,
        'port'     => $config->database->port,
        'username' => $config->database->username,
        'password' => $config->database->password,
        'dbname'   => $config->database->dbname,
        'charset'  => $config->database->charset
    ];

    if ($config->database->adapter == 'Postgresql') {
        unset($params['charset']);
    }

    $connection = new $class($params);

    return $connection;
});

$di->setShared('modelsMetadata', function () {
    return new MetaDataAdapter();
});

$di->set('modelsManager', function () {
    $manager = new ModelsManager(); 
    return $manager;
});

$di->set('flash', function () {
    $flash = new Flash();
    $flash->setAutomaticHtml(false);

	return $flash;
});

$di->setShared('session', function () {
    session_name('phalcon-base');
    $options = [
        'uniqueId'   => 'phalcon-base',
        'persistent' => true,
        'lifetime'   => 3600,
        'prefix'     => 'ph_b_',
        'adapter'    => 'files',
    ];

    $session = SessionFactory::load($options);
    $session->start();

    return $session;
});

$di->set('security', function () {
    $security = new SecurityManager();
    $security->setWorkFactor(12);

    return $security;
}, true);

$di->set('crypt', function () {
    $config = $this->getConfig();
    
    $crypt = new CryptManager();
    $crypt->setKey($config->application->cryptSalt);

    return $crypt;
}, true);

$di->set('cookies', function () {
    $cookies = new CookiesManager();
    $cookies->useEncryption(true);

    return $cookies;
});

$di->setShared('status', function () {
    $config = $this->getConfig();

    $options = [
        'maintenance' => $config->status->maintenance,
        'debuggable' => $config->status->debuggable
    ];

    $status = (object) $options;

    return $status;
});

$di->set('dispatcher', function () {
    $dispatcher = new Dispatcher();
    $dispatcher->setDefaultNamespace('App\Controllers');

    return $dispatcher;
});

$di->set('admin_auth', function () {
    return new AdminAuth();
});

$di->setShared('AclResources', function () {
    $resources = [];
    if (is_readable(APP_PATH . '/config/acl.php')) {
        $resources = include APP_PATH . '/config/acl.php';
    }
    return $resources;
});

$di->set('acl', function () {
    $acl = new Permissions();
    $arrResources = $this->getShared('AclResources')->toArray();
    $acl->addResources($arrResources);

    return $acl;
});

$di->set('logger', function ($filename = null, $format = null) {
    $config = $this->getConfig();

    $format   = $format ?: $config->get('logger')->format;
    $filename = trim($filename ?: $config->get('logger')->filename, '\\/');
    $path     = rtrim($config->get('logger')->logsDir, '\\/') . DIRECTORY_SEPARATOR;

    $formatter = new FormatterLine($format, $config->get('logger')->date);
    $logger    = new FileLogger($path . $filename);

    $logger->setFormatter($formatter);
    $logger->setLogLevel($config->get('logger')->logLevel);

    return $logger;
});

$di->set('mailer', function () {
    return new Mailer();
});
