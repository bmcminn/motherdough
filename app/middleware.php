<?php
declare(strict_types=1);


use Slim\App;
use Psr\Log\LoggerInterface;
use Tuupola\Middleware\JwtAuthentication;


return function (App $app) {

    // TODO: add a middleware that does timestamp request validation per https://restfulapi.net/security-essentials/
    $c = $app->getContainer();

    // add CSRF guard middleware
    // // NOTE: disabled since we do not really need CSRF in the API itself
    // $app->add($DI->get('csrf'));



    // Setup Auth Middleware
    // $app->add($DI->get('auth_middleware'));


    // Setup JWT middleware
    $authMiddleware = new JwtAuthentication([
        'secure'    => IS_PROD,
        'algorithm' => explode('|', env('JWT_ALGORITHM', '')),
        'logger'    => $c->get('logger'),
        'path'      => '/api',
        'secret'    => env('JWT_SECRET'),
        'error' => function ($res, $e) {
            $data = [];
            $data['status']  = 'error';
            $data['message'] = $e['message'];

            return $res->withJson($data, 401);
        }
    ]);

    $app->add($authMiddleware);

};
