<?php

use RedBeanPHP\Facade as R;
use Slim\Factory\AppFactory;


require __DIR__ . '/../vendor/autoload.php';


// // INITIALIZE SERVICES SETUP
// require __DIR__ . '/setup.php';


// INIT APPLICATION
$app = AppFactory::create();


// SETUP MIDDLEWARE
$app->addRoutingMiddleware();

$app->addBodyParsingMiddleware();

$app->addErrorMiddleware(
    $displayErrorDetails=true,
    $logErrors=true,
    $logErrorDetails=true
);


if (getenv('PHP_ENV') === 'production') {

    // SETUP ROUTE CACHER
    $routeCollector = $app->getRouteCollector();
    $routeCollector->setCacheFile(__DIR__ . '/../storage/cache/routes.cache');

    // Freeze the database
    R::freeze(TRUE);
}


// ADD ROUTES
require __DIR__ . '/routes/AuthController.php';
require __DIR__ . '/routes/UserController.php';
require __DIR__ . '/routes/AppController.php';


// START APP
$app->run();


// CLEANUP
R::close();
