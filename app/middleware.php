<?php

use Slim\App;


return function (App $app) {

    $DI         = $app->getContainer();
    $settings   = $DI->get('settings');
    $logger     = $DI->get('logger');


    // TODO: add a middleware that does timestamp request validation per https://restfulapi.net/security-essentials/


    // RUN ME LAST IN DEV
    if ($settings['isDev']) {
        $app->add(\App\Middleware\DebugMiddleware::class);
    }


    $app->add('auth_jwt');


};
