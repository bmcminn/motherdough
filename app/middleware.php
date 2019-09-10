<?php

use Slim\App;


return function (App $app) {

    $DI = $app->getContainer();


    // TODO: should these be per route/group middlewares?

    // add CSRF guard middleware
    $app->add($DI->get('csrf'));


    // Setup Auth Middleware
    $app->add($DI->get('auth_middleware'));

};
