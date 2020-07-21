<?php

session_start(); // enable Sessions mechanism for the entire web application

require_once 'vendor/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// create a log channel
$log = new Logger('main');
$log->pushHandler(new StreamHandler(dirname(__FILE__).'/logs/everything.log', Logger::DEBUG));    //   Log Debug+ 
$log->pushHandler(new StreamHandler(dirname(__FILE__).'/logs/errors.log', Logger::ERROR));  //   Log Errors+

if (strpos($_SERVER['HTTP_HOST'], "ipd21.com") !== false) {
    //  Hosting on ipd21.com
    DB::$dbName = 'cp4976_classnotes';
    DB::$user = 'cp4976_classnotes';
    DB::$password = 'Xe00rpz64k9c';
} else {
    //  Local Hosting
    DB::$dbName = 'classnotes';
    DB::$user = 'classnotes';
    DB::$password = 'PLBeZhWJK6G7bS7L';
    DB::$port = 3333;
}

DB::$error_handler = 'db_error_handler';    //  Runs on mysql query errors
DB::$nonsql_error_handler = 'db_error_handler';    //  Runs on library errors (bad syntax... etc.)

function db_error_handler($params) {
    global $log;
    //  Log First
    $log->error("Database error: " . $params['error']);
    if (isset($params['query'])) {
        $log->error("SQL query: " . $params['query']);
    }
    //  Redirect 
    header("Location: /internalerror");
    die;
}

// Create and configure Slim app
$config = ['settings' => [
    'addContentLengthHeader' => false,
    'displayErrorDetails' => true
]];
$app = new \Slim\App($config);

// Fetch DI Container
$container = $app->getContainer();

// Register Twig View helper
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig(dirname(__FILE__) . '/templates', [
        'cache' => dirname(__FILE__) . '/cache',
        'debug' => true, // This line should enable debug mode
    ]);
    // Instantiate and add Slim specific extension
    $router = $c->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
    $view->addExtension(new \Slim\Views\TwigExtension($router, $uri));
    return $view;
};

//  All templates will be given the userSession variable
//  Note: You can also supress the warning with @$_SESSION['user']
$container['view']->getEnvironment()->addGlobal('userSession',$_SESSION['user'] ?? null);