<?php

use Slim\App;


return function (App $app) {

    $DI         = $app->getContainer();
    $settings   = $DI->get('settings');
    $logger     = $DI->get('logger');


    // TODO: add a middleware that does timestamp request validation per https://restfulapi.net/security-essentials/

    $app->add('auth_jwt');

};
