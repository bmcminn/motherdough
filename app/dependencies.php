<?php

use Slim\App;


return function (App $app) {

    $DI = $app->getContainer();

    $DI['auth']         = new \App\Dependencies\Auth();
    $DI['auth_jwt']     = new \App\Dependencies\AuthJwt();
    $DI['csrf']         = new \App\Dependencies\CSRF();
    $DI['database']     = new \App\Dependencies\DB();
    $DI['logger']       = new \App\Dependencies\Logger();

    // $DI['renderer'] = function ($c) {
    //     $settings = $c->get('settings')['renderer'];
    //     return new \Slim\Views\PhpRenderer($settings['template_path']);
    // };


};
