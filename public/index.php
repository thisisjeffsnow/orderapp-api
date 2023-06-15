<?php
define('LOG_FILE', __DIR__ . '/../logs/access.log');

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\Response\JsonResponse;
use Middleware\{
    Logger,
    ListHandler,
    InsertHandler,
    DeleteHandler,
    CustomerListHandler,
    CustomerInsertHandler,
    CustomerDeleteHandler,
    NextHandler
};

// setup creds
Dotenv::createImmutable(__DIR__ . "/..")->load();
$dbName = $_ENV['DBNAME'];
$dbUser = $_ENV['DBUSER'];
$dbPwd = $_ENV['DBPWD'];

define('DB_CONFIG', ['dbname' => $dbName, 'dbuser' => $dbUser, 'dbpwd' => $dbPwd]);

// build the pipe
$pipe = [
    Logger::class => NextHandler::class,
    DeleteHandler::class => NextHandler::class,
    InsertHandler::class => NextHandler::class,
    ListHandler::class => NULL,
];
// build a PSR-7 Request object
$request = ServerRequestFactory::fromGlobals();
// run the pipe
foreach ($pipe as $key => $val) {
    $middleware = new $key();

    $handler = (!empty($val)) ? new $val() : NULL;
    if (method_exists($middleware, 'process')) {
        $response = $middleware->process($request, $handler);
    } else {
        $response = $middleware->handle($request);
    }
    // check response: is it time to stop?
    $code = $response->getStatusCode();
    if ($code !== 202) break;
}
echo $response->getBody();
