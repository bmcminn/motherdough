<?php

declare(strict_types=1);

use App\Handlers\HttpErrorHandler;
use App\Handlers\ShutdownHandler;
use App\ResponseEmitter\ResponseEmitter;
use DI\ContainerBuilder;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;


require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/bootstrap.php';


// Instantiate PHP-DI ContainerBuilder
$containerBuilder = new ContainerBuilder();

if (IS_PROD) { // Should be set to true in production
    $containerBuilder->enableCompilation(ROOT_DIR . '/var/cache');
}


// Set up settings
$settings = require(ROOT_DIR . '/app/settings.php');
$settings($containerBuilder);


// // Set up dependencies
// $dependencies = require(ROOT_DIR . '/app/dependencies.php');
// $dependencies($containerBuilder);


// // Set up repositories
// $repositories = require(ROOT_DIR . '/app/repositories.php');
// $repositories($containerBuilder);


// Build PHP-DI Container instance
$container = $containerBuilder->build();


// Instantiate the app
AppFactory::setContainer($container);
$app = AppFactory::create();
$callableResolver = $app->getCallableResolver();


// Register middleware
$middleware = require(ROOT_DIR . '/app/middleware.php');
$middleware($app);


// Register routes
$routes = require(ROOT_DIR . '/app/routes.php');
$routes($app);

// /** @var bool $displayErrorDetails */
// $displayErrorDetails = $container->get('settings')['displayErrorDetails'];


// // Create Request object from globals
// $serverRequestCreator = ServerRequestCreatorFactory::create();
// $request = $serverRequestCreator->createServerRequestFromGlobals();


// // Create Error Handler
// $responseFactory = $app->getResponseFactory();
// $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);


// // Create Shutdown Handler
// $shutdownHandler = new ShutdownHandler($request, $errorHandler, $displayErrorDetails);
// register_shutdown_function($shutdownHandler);


// // Add Routing Middleware
// $app->addRoutingMiddleware();


// // Add Error Middleware
// $errorMiddleware = $app->addErrorMiddleware($displayErrorDetails, false, false);
// $errorMiddleware->setDefaultErrorHandler($errorHandler);
// // Run App & Emit Response
// $response = $app->handle($request);
// $responseEmitter = new ResponseEmitter();
// $responseEmitter->emit($response);

$app->run();
