<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


// TODO: implement DelightIM\Auth: https://github.com/delight-im/PHP-Auth#email-verification


return function(App $app) {

    $DI = $app->getContainer();


    // LOGIN ROUTE
    // ------------------------------------------------------------
    $app->post('/login', function(Request $req, Response $res) use ($app, $DI){

        $auth = $DI->get('auth.controller');

        $body = $req->getParsedBody();

        $data = [
            'message'   => 'auth base route!',
            'req'       => $body,
        ];


        $errors = [
            0 => [],
            1 => [],
            2 => [],
            3 => [],
            4 => [],
        ];


        $statusCode = 200;


        try {
            $data['user']       = $auth->login($body['email'], $body['password']);
            $data['message']    = 'User is logged in';
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            $data['message']    = IS_DEV ? 'Wrong email' : 'Wrong login credentails';
            $statusCode = 400;
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            $data['message']    = IS_DEV ? 'Wrong password' : 'Wrong login credentails';
            $statusCode = 400;
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $data['message']    = 'Email not verified';
            $statusCode = 400;
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            $data['message']    = 'Too many requests';
            $statusCode = 400;
        }


        return $res->withJson($data, $statusCode);
    });



    // LOGOUT/SESSION DESTROY
    // ------------------------------------------------------------
    $app->post('/logout', function(Request $req, Response $res) use ($DI){

        $data = [
            'message' => 'auth logout route!',
        ];

        session_destroy();

        return $res->withJson($data);
    });


    // USER REGISTRATION
    // ------------------------------------------------------------
    $app->post('/register', function(Request $req, Response $res) use ($DI){

        $data = [
            'message' => 'auth register route!',
        ];

        return $res->withJson($data);
    });


    // USER CONFIRMATION
    // ------------------------------------------------------------
    $app->post('/confirmation', function(Request $req, Response $res) use ($DI){


        try {
            $auth->confirmEmail($_GET['selector'], $_GET['token']);

            echo 'Email address has been verified';
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            die('Token expired');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }


        $data = [
            'message' => 'auth confirmation route!',
        ];

        return $res->withJson($data);
    });


    // USER DEACTIVATION
    // ------------------------------------------------------------
    $app->post('/deactivate', function(Request $req, Response $res) use ($DI){

        $data = [
            'message' => 'auth deactivation route!',
        ];

        return $res->withJson($data);
    })
        ->add($DI->get('auth_middleware'))
        ->setName('auth_deactivate')
        ;

};
