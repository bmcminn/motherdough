<?php

use Slim\App;
// use Slim\Http\Request;
// use Slim\Http\Response;


if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';


// Instantiate the app
$settings = require ROOT_DIR . '/app/settings.php';
$app = new App($settings);


// Set up dependencies
$dependencies = require ROOT_DIR . '/app/dependencies.php';
$dependencies($app);


// Register middleware
$middleware = require ROOT_DIR . '/app/middleware.php';
$middleware($app);


// Register routes
$routes = require ROOT_DIR . '/app/routes.php';
$routes($app);


// Run app
$app->run();
