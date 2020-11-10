<?php

use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Http\Response;

error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

try {
	$di = new FactoryDefault();

	$config = include APP_PATH . '/config/config.php';

	include APP_PATH . '/config/services.php';

	include APP_PATH . '/config/loader.php';

	include APP_PATH . '/config/router.php';

	$application = new Application($di);
	$application->useImplicitView(false);

	echo $application->handle()->getContent();

} catch(Exception $e) {
	$exception_type = get_class($e);
	$exception_msg = $e->getMessage();
	$exception_trace = $e->getTraceAsString();

    $debuggable = $di->get('status')->debuggable;

    if ($debuggable == false) {
        $response = new Response();
        $response->setStatusCode(500);
        $response->send();
		$di->get('view')->render('errors', '500');
    } else {
	    echo '(' . $exception_type . ') ' . $exception_msg . '<br>';
		echo nl2br(htmlentities($exception_trace));
    }

	$logger = $di->get('logger');
	$logger->begin();
	$logger->error(
		$exception_type . ' - ' . $exception_msg . PHP_EOL . $exception_trace
	);
	$logger->commit();
}
