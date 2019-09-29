<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Delight\Auth;
use Slim\Http\Request;
use Slim\Http\Response;


class AuthController extends BaseController {

    // TODO: fully implement changeEmail
    public function changeEmail(Request $req, Response $res) {

        $data = [];

        $body   = $req->getParsedBody();

        $data['message'] = 'auth base route!';


        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'Password has been changed' ],
            1 => [ 400, 'Invalid email address' ],
            2 => [ 400, 'Email address already exists' ],
            3 => [ 429, 'Account not verified' ],
            3 => [ 429, 'Not logged in' ],
            3 => [ 429, 'Too many requests' ],
        ];


        try {
            if ($this->auth->reconfirmPassword($_POST['password'])) {
                $this->auth->changeEmail($_POST['newEmail'], function ($selector, $token) {
                    echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email to the *new* address)';
                });

                echo 'The change will take effect as soon as the new email address has been confirmed';
            }
            else {
                echo 'We can\'t say if the user is who they claim to be';
            }

            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
        }
        catch (Auth\InvalidEmailException $e) {
            die('Invalid email address');
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
        }
        catch (Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
        }
        catch (Auth\EmailNotVerifiedException $e) {
            die('Account not verified');
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
        }
        catch (Auth\NotLoggedInException $e) {
            die('Not logged in');
            $statusCode         = $status[4][0];
            $data['message']    = $status[4][1];
        }
        catch (Auth\TooManyRequestsException $e) {
            die('Too many requests');
            $statusCode         = $status[5][0];
            $data['message']    = $status[5][1];
        }

        return $res->withJson($data);
    }


    // TODO: fully implement changePassword
    public function changePassword(Request $req, Response $res) {

        $data = [];

        $body   = $req->getParsedBody();

        $data['message'] = 'auth base route!';


        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'Password has been changed' ],
            1 => [ 400, 'Not logged in' ],
            2 => [ 400, 'Invalid password(s)' ],
            3 => [ 429, 'Too many requests' ],
        ];



        try {
            $this->auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];
        }
        catch (Auth\NotLoggedInException $e) {
            // die('Not logged in');
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
        }
        catch (Auth\InvalidPasswordException $e) {
            // die('Invalid password(s)');
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
        }
        catch (Auth\TooManyRequestsException $e) {
            // die('Too many requests');
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
        }

        return $res->withJson($data);
    }


    // TODO: fully implement account confirmation
    public function confirmation(Request $req, Response $res) {

        $data = [];

        $body   = $req->getParsedBody();

        $data['message'] = 'auth base route!';


        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'Request has been generated' ],
            1 => [ 400, 'Invalid token' ],
            2 => [ 400, 'Token expired' ],
            3 => [ 401, 'Email address already exists' ],
            4 => [ 429, 'Too many requests' ],
        ];


        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];
        }
        catch (Auth\InvalidSelectorTokenPairException $e) {
            // die('Invalid token');
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
        }
        catch (Auth\TokenExpiredException $e) {
            // die('Token expired');
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
        }
        catch (Auth\UserAlreadyExistsException $e) {
            // die('Email address already exists');
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
        }
        catch (Auth\TooManyRequestsException $e) {
            // die('Too many requests');
            $statusCode         = $status[4][0];
            $data['message']    = $status[4][1];
        }

        return $res->withJson($data);
    }


    // TODO: fully implement account deactivation
    public function deactivate(Request $req, Response $res) {

        $data = [
            'message' => 'auth deactivation route!',
        ];

        return $res->withJson($data);
    }


    // TODO: fully integrate forgotPassword
    public function forgotPassword(Request $req, Response $res) {

        $data = [];

        $body   = $req->getParsedBody();

        $data['message'] = 'auth base route!';


        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'Request has been generated' ],
            1 => [ 400, 'Invalid email address' ],
            2 => [ 400, 'Email not verified' ],
            3 => [ 401, 'Password reset is disabled' ],
            4 => [ 429, 'Too many requests' ],
        ];


        try {
            $this->auth->forgotPassword($_POST['email'], function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
            });

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];
        }
        catch (Auth\InvalidEmailException $e) {
            // die('Invalid email address');
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
        }
        catch (Auth\EmailNotVerifiedException $e) {
            // die('Email not verified');
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
        }
        catch (Auth\ResetDisabledException $e) {
            // die('Password reset is disabled');
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
        }
        catch (Auth\TooManyRequestsException $e) {
            // die('Too many requests');
            $statusCode         = $status[4][0];
            $data['message']    = $status[4][1];
        }

        $data = [
            'message' => 'auth deactivation route!',
        ];

        return $res->withJson($data);
    }



    /**
     * @param  Request
     * @param  Response
     * @return [type]
     */
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



    /**
     * @param  Request
     * @param  Response
     * @return [type]
     */
    public function logout(Request $req, Response $res) {

        $data = [];

        $body   = $req->getParsedBody();

        $data['message'] = 'auth base route!';


        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'Login successful' ],
            1 => [ 400, 'Wrong login credentails', 'Wrong email' ],
        ];


        try {
            $this->auth->logOutEverywhere();
            $this->auth->destroySession();
            session_destroy();
        }
        catch (Auth\NotLoggedInException $e) {
            die('Not logged in');
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
        }

        return $res->withJson($data);
    }



    /**
     * @param  Request
     * @param  Response
     * @return [type]
     */
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


    // TODO: fully integrate resetPassword
    public function resetPassword(Request $req, Response $res) {

        $body = $req->getParsedBody();

        $data = [];
        $data['message'] = 'auth base route!';
        $data['success'] = true;


        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'Password has been reset' ],
            1 => [ 400, 'Invalid token' ],
            2 => [ 401, 'Token expired' ],
            3 => [ 403, 'Password reset is disabled' ],
            4 => [ 400, 'Invalid password' ],
            5 => [ 429, 'Too many requests' ],
        ];




        try {
            $this->auth->resetPassword($_POST['selector'], $_POST['token'], $_POST['password']);

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];

        }
        catch (Auth\InvalidSelectorTokenPairException $e) {
            // die('Invalid token');
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
            $data['success']    = false;
        }
        catch (Auth\TokenExpiredException $e) {
            // die('Token expired');
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
            $data['success']    = false;
        }
        catch (Auth\ResetDisabledException $e) {
            // die('Password reset is disabled');
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
            $data['success']    = false;
        }
        catch (Auth\InvalidPasswordException $e) {
            // die('Invalid password');
            $statusCode         = $status[4][0];
            $data['message']    = $status[4][1];
            $data['success']    = false;
        }
        catch (Auth\TooManyRequestsException $e) {
            // die('Too many requests');
            $statusCode         = $status[5][0];
            $data['message']    = $status[5][1];
            $data['success']    = false;
        }

        return $res->withJson($data);
    }


    // TODO: fully integrate resendConfirmation
    public function resendConfirmation(Request $req, Response $res) {

        $body = $req->getParsedBody();

        $data = [];
        $data['message'] = '';
        $data['success'] = true;


        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'The user may now respond to the confirmation request (usually by clicking a link)' ],
            1 => [ 404, 'No earlier request found that could be re-sent' ],
            2 => [ 429, 'There have been too many requests -- try again later' ],
        ];


        try {
            $this->auth->resendConfirmationForEmail($_POST['email'], function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
            });

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];
        }
        catch (Auth\ConfirmationRequestNotFound $e) {
            // die('No earlier request found that could be re-sent');
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
            $data['success']    = false;
        }
        catch (Auth\TooManyRequestsException $e) {
            // die('There have been too many requests -- try again later');
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
            $data['success']    = false;
        }

        return $res->withJson($data);
    }


    // TODO: fully integrate verifyPasswordReset
    public function verifyPasswordReset(Request $req, Response $res) {

        $body = $req->getParsedBody();

        $data = [];
        $data['message'] = '';
        $data['success'] = true;


        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'Please enter your new password' ],
            1 => [ 404, 'Invalid token' ],
            2 => [ 429, 'Token expired' ],
            3 => [ 403, 'Password reset is disabled' ],
            4 => [ 429, 'Too many requests' ],
        ];


        try {
            $this->auth->canResetPasswordOrThrow($_GET['selector'], $_GET['token']);

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];
        }
        catch (Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];
            $data['success']    = false;
        }
        catch (Auth\TokenExpiredException $e) {
            die('Token expired');
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
            $data['success']    = false;
        }
        catch (Auth\ResetDisabledException $e) {
            die('Password reset is disabled');
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
            $data['success']    = false;
        }
        catch (Auth\TooManyRequestsException $e) {
            die('Too many requests');
            $statusCode         = $status[4][0];
            $data['message']    = $status[4][1];
            $data['success']    = false;
        }

        return $res->withJson($data);
    }


}
