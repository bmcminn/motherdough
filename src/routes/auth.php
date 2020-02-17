<?php

use App\Controllers\AuthController;
use App\Middleware\AuthValidation;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


return function(App $app) {

    $DI = $app->getContainer();

    // TODO: implement DelightIM\Auth: https://github.com/delight-im/PHP-Auth#email-verification

    $app->post('/login',        AuthController::class . ':login')
        ->add(AuthValidation::class . ':login')
        ->setName('auth_login')
        ;


    $app->post('/logout',       AuthController::class . ':logout')
        ->setName('auth_logout')
        ;


    $app->post('/register',     AuthController::class . ':register')
        // TODO: setup register auth middleware method
        // ->add(AuthValidation::class . ':register')
        ->setName('auth_register')
        ;


    $app->get('/confirmation',  AuthController::class . ':confirmation')
        // TODO: confirmation register auth middleware method
        // ->add(AuthValidation::class . ':confimration')
        ->setName('auth_confirmation')
        ;


    $app->post('/deactivate',   AuthController::class . ':deactivate')
        // TODO: setup deactivate auth middleware method
        // ->add(AuthValidation::class . ':deactivate')
        // ->add($DI->get('auth_middleware'))
        ->setName('auth_deactivate')
        ;

};
