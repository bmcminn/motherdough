<?php

use Slim\App;
use Tuupola\Middleware\JwtAuthentication;


return function (App $app) {

    $DI = $app->getContainer();


    // TODO: add a middleware that does timestamp request validation per https://restfulapi.net/security-essentials/


    // add CSRF guard middleware
    // // NOTE: disabled since we do not really need CSRF in the API itself
    // $app->add($DI->get('csrf'));



    // Setup Auth Middleware
    // $app->add($DI->get('auth_middleware'));


    // Setup JWT middleware
    $app->add(new JwtAuthentication([
        'algorithm' => explode('|', env('APP_JWT_ALGORITHM')),
        'logger'    => $DI->get('logger'),
        'path'      => '/api', /* or ["/api", "/admin"] */
        'secret'    => env('JWT_SECRET', 'supersecretkeyyoushouldnotcommittogithub'),
    ]));

};
