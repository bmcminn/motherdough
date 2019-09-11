<?php

use Delight\Db;
use Slim\App;
use Tuupola\Middleware\JwtAuthentication;


return function (App $app) {

    $DI = $app->getContainer();


    // SETUP CSRF UTILITY
    // ----------------------------------------------------------------------

    $DI['csrf'] = function ($c) {
        return new \Slim\Csrf\Guard;
    };



    // SETUP UI RENDERER INSTANCE
    // ----------------------------------------------------------------------

    // $DI['renderer'] = function ($c) {
    //     $settings = $c->get('settings')['renderer'];
    //     return new \Slim\Views\PhpRenderer($settings['template_path']);
    // };



    // SETUP LOGGER INSTANCE
    // ----------------------------------------------------------------------

    $DI['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };



    // SETUP DB CONNECTION
    // ----------------------------------------------------------------------

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
            }
        ]);

        return $authMiddleware;
    };



    // SETUP DB CONNECTION
    // ----------------------------------------------------------------------

    $DB_DATABASE = env('DB_DATABASE', 'sqlite');
    $DB_PATH     = buildPath(DATA_DIR, env('DB_FILEPATH', ''));

    $db = Db\PdoDatabase::fromDsn(
        new Db\PdoDsn(
            "{$DB_DATABASE}:{$DB_PATH}",
            env('DB_USERNAME', null),
            env('DB_PASSWORD', null)
        )
    );

    $DI['database'] = $db;



    // SETUP AUTH CONTROLLER
    // ----------------------------------------------------------------------

    $DI['auth.controller'] = new \Delight\Auth\Auth($db);


};
