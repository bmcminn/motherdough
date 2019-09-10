<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


// TODO: implement DelightIM\Auth: https://github.com/delight-im/PHP-Auth#email-verification


return function(App $app) {

    $DI = $app->getContainer();


    // base auth path
    // ------------------------------------------------------------
    $app->get('/', function(Request $req, Response $res) {

        $data = [
            'message' => 'auth base route!',
        ];

        return $res->withJson($data);
    });


    // LOGIN ROUTE
    // ------------------------------------------------------------
    $app->post('/login', function(Request $req, Response $res) {

        $data = [
            'message' => 'auth login route!',
        ];

        return $res->withJson($data);
    });


    // LOGOUT/SESSION DESTROY
    // ------------------------------------------------------------
    $app->post('/logout', function(Request $req, Response $res) {

        $data = [
            'message' => 'auth logout route!',
        ];

        session_destroy();

        return $res->withJson($data);
    });


    // USER REGISTRATION
    // ------------------------------------------------------------
    $app->post('/register', function(Request $req, Response $res) {

        $data = [
            'message' => 'auth register route!',
        ];

        return $res->withJson($data);
    });


    // USER CONFIRMATION
    // ------------------------------------------------------------
    $app->post('/confirmation', function(Request $req, Response $res) {

        $data = [
            'message' => 'auth confirmation route!',
        ];

        return $res->withJson($data);
    });


    // USER DEACTIVATION
    // ------------------------------------------------------------
    $app->post('/deactivate', function(Request $req, Response $res) {

        $data = [
            'message' => 'auth deactivation route!',
        ];

        return $res->withJson($data);
    })
        ->add($DI->get('auth_middleware'))
        ->setName('auth_deactivate')
        ;

};
