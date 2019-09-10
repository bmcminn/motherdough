<?php

use Slim\App;
use Tuupola\Middleware\JwtAuthentication;


return function (App $app) {
    $DI = $app->getContainer();


    $DI['csrf'] = function ($c) {
        return new \Slim\Csrf\Guard;
    };


    // // view renderer
    // $DI['renderer'] = function ($c) {
    //     $settings = $c->get('settings')['renderer'];
    //     return new \Slim\Views\PhpRenderer($settings['template_path']);
    // };


    // monolog
    $DI['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };



    $DI['auth_middleware'] = function($c) {

        $authMiddleware = new JwtAuthentication([
            'secure'    => IS_PROD,
            'logger'    => $c->get('logger'),
            'path'      => '/api',
            'secret'    => env('APP_JWT_SECRET', 'ITS A SECRET TO EVERYBODY.'),
            'error' => function ($res, $e) {
                $data = [];
                $data['status']  = 'error';
                $data['message'] = $e['message'];

                return $res->withJson($data, 401);
                // return $res
                //     ->withHeader('Content-Type', 'application/json')
                //     ->getBody()
                //     ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
            }
        ]);

        return $authMiddleware;
    };
};
