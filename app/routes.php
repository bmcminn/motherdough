<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


return function (App $app) {

    $DI = $app->getContainer();


    $app->get('/[{name}]',
        function (Request $request, Response $response, array $args)
        use ($DI) {
            // Sample log message
            $DI->get('logger')->info("Slim-Skeleton '/' route");

            // Render index view
            return $response->write('hello');
        });


    $authRoutes = require ROUTES_DIR . '/auth.php';
    $app->group('/auth', $authRoutes);


    $apiRoutes = require ROUTES_DIR . '/api.php';
    $app->group('/api', $apiRoutes);


};
