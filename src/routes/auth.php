<?php

use App\Controllers\AuthController;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


return function(App $app) {

    $DI = $app->getContainer();

    // TODO: implement DelightIM\Auth: https://github.com/delight-im/PHP-Auth#email-verification

    $app->post('/login',        AuthController::class . ':login')
        ->setName('auth_login')
        ;


    $app->post('/logout',       AuthController::class . ':logout')
        ->setName('auth_logout')
        ;


    $app->post('/register',     AuthController::class . ':register')
        ->setName('auth_register')
        ;


    $app->get('/confirmation',  AuthController::class . ':confirmation')
        ->setName('auth_confirmation')
        ;


    $app->post('/deactivate',   AuthController::class . ':deactivate')
        // ->add($DI->get('auth_middleware'))
        ->setName('auth_deactivate')
        ;

};
