<?php

namespace App\Controllers;

use Delight\Auth;
use Slim\Http\Request;
use Slim\Http\Response;


class AuthController {

    public $ctx;
    public $auth;
    public $logger;
    public $settings;


    public function __construct(\Slim\Container $ctx) {
        $this->ctx      = $ctx;
        $this->auth     = $ctx->get('auth');
        $this->logger   = $ctx->get('logger');
        $this->settings = $ctx->get('settings');
    }


    public function login(Request $req, Response $res) {

        $data = [];

        $body   = $req->getParsedBody();

        $data['message'] = 'auth base route!';


        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'Login successful' ],
            1 => [ 400, 'Wrong login credentails', 'Wrong email' ],
            2 => [ 400, 'Wrong login credentails', 'Wrong password' ],
            3 => [ 401, 'Email not verified' ],
            4 => [ 429, 'Too many requests' ],
        ];


        try {
            $this->auth->login($body['email'], $body['password']);

            $data['user'] = [
                'id'        => $this->auth->getUserId(),
                'roles'     => $this->auth->getRoles(),
            ];


            $token = generateToken($data['user'], $this->auth->getRoles());
            $data['token'] = $token;


            // TODO: make session.controller to generate auth token and return to user
            $this->logger->info($status[0][1], [
                'ip'        => $this->auth->getIpAddress(),
                'userId'    => $this->auth->getUserId(),
            ]);

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];
        }

        catch (Auth\InvalidEmailException $e) {
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];

            $this->logger->warning($status[1][1], [
                'ip'        => $this->auth->getIpAddress(),
                'userId'    => $this->auth->getUserId(),
            ]);
        }

        catch (Auth\InvalidPasswordException $e) {
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
        }

        catch (Auth\EmailNotVerifiedException $e) {
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
        }

        catch (Auth\TooManyRequestsException $e) {
            $statusCode         = $status[4][0];
            $data['message']    = $status[4][1];
        }

        return $res->withJson($data, $statusCode);
    }


    public function logout(Request $req, Response $res) {

        $data = [
            'message' => 'auth logout route!',
        ];

        session_destroy();

        return $res->withJson($data);
    }


    public function register(Request $req, Response $res) {

        $body = $req->getParsedBody();

        $data = [];
        $data['message'] = 'auth base route!';


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
            $data['userId'] = $this->auth->register(
                $body['email'],
                $body['password'],
                $body['username']
                // fourth argument is a callback to send registratino confirmation email
            );

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];
        }

        catch (Auth\InvalidEmailException $e) {
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
            $data['success']    = false;
        }

        catch (Auth\InvalidPasswordException $e) {
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
            $data['success']    = false;
        }

        catch (Auth\UserAlreadyExistsException $e) {
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
            $data['success']    = false;
        }

        catch (Auth\TooManyRequestsException $e) {
            $statusCode         = $status[4][0];
            $data['message']    = $status[4][1];
            $data['success']    = false;
        }


        return $res->withJson($data);
    }


    public function confirmation(Request $req, Response $res) {

        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);

            echo 'Email address has been verified';
        }

        catch (Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        }

        catch (Auth\TokenExpiredException $e) {
            die('Token expired');
        }

        catch (Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
        }

        catch (Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }


        $data = [
            'message' => 'auth confirmation route!',
        ];

        return $res->withJson($data);
    }


    public function deactivate(Request $req, Response $res) {

        $data = [
            'message' => 'auth deactivation route!',
        ];

        return $res->withJson($data);
    }


}
