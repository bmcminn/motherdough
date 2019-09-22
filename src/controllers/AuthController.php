<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Delight\Auth;
use Slim\Http\Request;
use Slim\Http\Response;


class AuthController extends BaseController {

    // TODO: fully implement changeEmail
    public function changeEmail(Request $req, Response $res) {

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
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Invalid email address');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Account not verified');
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            die('Not logged in');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }

        return $res->withJson($data);
    }


    // TODO: fully implement changePassword
    public function changePassword(Request $req, Response $res) {
        try {
            $this->auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);

            echo 'Password has been changed';
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            die('Not logged in');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password(s)');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }

        return $res->withJson($data);
    }


    // TODO: fully implement account confirmation
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


    // TODO: fully implement account deactivation
    public function deactivate(Request $req, Response $res) {

        $data = [
            'message' => 'auth deactivation route!',
        ];

        return $res->withJson($data);
    }


    // TODO: fully integrate forgotPassword
    public function forgotPassword(Request $req, Response $res) {

        try {
            $this->auth->forgotPassword($_POST['email'], function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
            });

            echo 'Request has been generated';
        }
        catch (Auth\InvalidEmailException $e) {
            die('Invalid email address');
        }
        catch (Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        }
        catch (Auth\ResetDisabledException $e) {
            die('Password reset is disabled');
        }
        catch (Auth\TooManyRequestsException $e) {
            die('Too many requests');
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

        try {
            $this->auth->logOutEverywhere();
            $this->auth->destroySession();
        }
        catch (\Delight\Auth\NotLoggedInException $e) {
            die('Not logged in');
        }
        session_destroy();

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
        try {
            $this->auth->resetPassword($_POST['selector'], $_POST['token'], $_POST['password']);

            echo 'Password has been reset';
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            die('Token expired');
        }
        catch (\Delight\Auth\ResetDisabledException $e) {
            die('Password reset is disabled');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }


        return $res->withJson($data);
    }


    // TODO: fully integrate resendConfirmation
    public function resendConfirmation(Request $req, Response $res) {

        try {
            $this->auth->resendConfirmationForEmail($_POST['email'], function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
            });

            echo 'The user may now respond to the confirmation request (usually by clicking a link)';
        }
        catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
            die('No earlier request found that could be re-sent');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('There have been too many requests -- try again later');
        }

        return $res->withJson($data);
    }


    // TODO: fully integrate verifyPasswordReset
    public function verifyPasswordReset(Request $req, Response $res) {
        try {
            $this->auth->canResetPasswordOrThrow($_GET['selector'], $_GET['token']);

            echo 'Put the selector into a "hidden" field (or keep it in the URL)';
            echo 'Put the token into a "hidden" field (or keep it in the URL)';

            echo 'Ask the user for their new password';
        }
        catch (Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        }
        catch (Auth\TokenExpiredException $e) {
            die('Token expired');
        }
        catch (Auth\ResetDisabledException $e) {
            die('Password reset is disabled');
        }
        catch (Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }


        return $res->withJson($data);
    }


}
