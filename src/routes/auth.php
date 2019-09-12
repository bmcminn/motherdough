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
        $logger = $DI->get('logger');

        $body = $req->getParsedBody();

        $data = [];
        $data['message'] = 'auth base route!';

        if (IS_DEV) {
            $data['req'] = $body;
        }

        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'Login successful' ],
            1 => [ 400, 'Wrong login credentails', 'Wrong email' ],
            2 => [ 400, 'Wrong login credentails', 'Wrong password' ],
            3 => [ 401, 'Email not verified' ],
            4 => [ 429, 'Too many requests' ],
        ];


        try {
            $auth->login($body['email'], $body['password']);

            $data['user'] = [
                'id'        => $auth->getUserId(),
                'roles'     => $auth->getRoles(),
            ];


            $token = generateToken($data['user'], $auth->getRoles());


            $data['token'] = $token;


            // TODO: make session.controller to generate auth token and return to user

            $logger->info($status[0][1], [
                'ip'        => $auth->getIpAddress(),
                'userId'    => $auth->getUserId(),
            ]);

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];
        }

        catch (\Delight\Auth\InvalidEmailException $e) {
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];

            $logger->warning($status[1][1], [
                'ip'        => $auth->getIpAddress(),
                'userId'    => $auth->getUserId(),
            ]);
        }

        catch (\Delight\Auth\InvalidPasswordException $e) {
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
        }

        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
        }

        catch (\Delight\Auth\TooManyRequestsException $e) {
            $statusCode         = $status[4][0];
            $data['message']    = $status[4][1];
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

        $auth = $DI->get('auth.controller');

        $body = $req->getParsedBody();

        $data = [
            'message'   => 'auth base route!',
            'req'       => $body,
        ];

        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'User registration was successful' ],
            1 => [ 400, 'Provided email was invalid' ],
            2 => [ 400, 'Provided password was invalid' ],
            3 => [ 409, 'That username is already taken' ],
            4 => [ 429, 'Too many requests' ],
        ];



        // if (env('AUTH_FORCE_EMAIL_CONFIRMATION', false)) {
        //     $emailConfirmation = function ($selector, $token) {
        //         echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
        //     }
        // }


        try {
            $data['userId'] = $auth->register(
                $body['email'],
                $body['password'],
                $body['username'],
                // fourth argument is a callback to send registratino confirmation email
            );

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];
        }

        catch (\Delight\Auth\InvalidEmailException $e) {
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
        }

        catch (\Delight\Auth\InvalidPasswordException $e) {
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
        }

        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
        }

        catch (\Delight\Auth\TooManyRequestsException $e) {
            $statusCode         = $status[4][0];
            $data['message']    = $status[4][1];
        }


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
