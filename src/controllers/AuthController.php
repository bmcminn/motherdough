<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Delight\Auth;
use Slim\Http\Request;
use Slim\Http\Response;


class AuthController extends BaseController {

    private $statusCode;
    private $statusMsg;

    private $resBody = [];


    private function setStatus(int $code, string $msg, string $msgVariant, array $ctx) {
        $this->statusCode           = $code;
        $this->resBody['success']   = $code >= 400 ? true : false;
        $this->resBody['message']   = trim($msg);

        // TODO: setup logging when certain $code values are met
        switch($code) {
            case 200:
                $this->logger->info($msg, $ctx);
                break;
            case 300:
            case 301:
            case 302:
            case 303:
            case 400:
            case 403:
                $this->logger->warning($msg, $ctx);
                break;
            case 200:
            case 500:
                $this->logger->error($msg, $ctx);
                break;
        }
    }



    // TODO: fully implement changeEmail
    /**
     * [changeEmail description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function changeEmail(Request $req, Response $res) {

        $body   = $req->getParsedBody();


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

            $this->setStatus(200, 'Password has been changed');
        }
        catch (Auth\InvalidEmailException $e) {
            $this->setStatus(400, 'Invalid email address');
            // die('Invalid email address');
        }
        catch (Auth\UserAlreadyExistsException $e) {
            $this->setStatus(400, 'Email address already exists');
            // die('Email address already exists');
        }
        catch (Auth\EmailNotVerifiedException $e) {
            $this->setStatus(429, 'Account not verified');
            // die('Account not verified');
        }
        catch (Auth\NotLoggedInException $e) {
            $this->setStatus(429, 'Not logged in');
            // die('Not logged in');
        }
        catch (Auth\TooManyRequestsException $e) {
            $this->setStatus(429, 'Too many requests');
            // die('Too many requests');
        }


        return $res->withJson($this->resBody, $this->statusCode);
    }


    // TODO: fully implement changePassword
    /**
     * [changePassword description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function changePassword(Request $req, Response $res) {

        $body   = $req->getParsedBody();


        try {
            $this->auth->changePassword($_POST['oldPassword'], $_POST['newPassword']);

            $this->setStatus( 200, 'Password has been changed' );
        }
        catch (Auth\NotLoggedInException $e) {
            $this->setStatus( 400, 'Not logged in' );
            // die('Not logged in');
        }
        catch (Auth\InvalidPasswordException $e) {
            $this->setStatus( 400, 'Invalid password(s)' );
            // die('Invalid password(s)');
        }
        catch (Auth\TooManyRequestsException $e) {
            $this->setStatus( 429, 'Too many requests' );
            // die('Too many requests');
        }


        return $res->withJson($this->resBody, $this->statusCode);
    }


    // TODO: fully implement account confirmation
    /**
     * [confirmation description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function confirmation(Request $req, Response $res) {

        $body   = $req->getParsedBody();


        try {
            $this->auth->confirmEmail($_GET['selector'], $_GET['token']);

            $this->setStatus(200, 'Request has been generated');
        }
        catch (Auth\InvalidSelectorTokenPairException $e) {
            $this->setStatus(400, 'Invalid token');
            // die('Invalid token');
        }
        catch (Auth\TokenExpiredException $e) {
            $this->setStatus(400, 'Token expired');
            // die('Token expired');
        }
        catch (Auth\UserAlreadyExistsException $e) {
            $this->setStatus(401, 'Email address already exists');
            // die('Email address already exists');
        }
        catch (Auth\TooManyRequestsException $e) {
            $this->setStatus(429, 'Too many requests');
            // die('Too many requests');
        }

        return $res->withJson($this->resBody, $this->statusCode);
    }


    // TODO: fully implement account deactivation
    /**
     * [deactivate description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function deactivate(Request $req, Response $res) {

        $data = [
            'message' => 'auth deactivation route!',
        ];

        return $res->withJson($this->resBody, $this->statusCode);
    }


    // TODO: fully integrate forgotPassword
    /**
     * [forgotPassword description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function forgotPassword(Request $req, Response $res) {

        $body   = $req->getParsedBody();


        try {
            $this->auth->forgotPassword($_POST['email'], function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
            });
            $this->setStatus(200, 'Request has been generated');

        }
        catch (Auth\InvalidEmailException $e) {
            $this->setStatus(400, 'Invalid email address');
            // die('Invalid email address');
        }
        catch (Auth\EmailNotVerifiedException $e) {
            $this->setStatus(400, 'Email not verified');
            // die('Email not verified');
        }
        catch (Auth\ResetDisabledException $e) {
            $this->setStatus(401, 'Password reset is disabled');
            // die('Password reset is disabled');
        }
        catch (Auth\TooManyRequestsException $e) {
            $this->setStatus(429, 'Too many requests');
            // die('Too many requests');
        }


        return $res->withJson($this->resBody, $this->statusCode);
    }



    /**
     * [login description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function login(Request $req, Response $res) {

        $body   = $req->getParsedBody();


        try {
            $this->auth->login($body['email'], $body['password']);

            $data['user'] = [
                'id'        => $this->auth->getUserId(),
                'roles'     => $this->auth->getRoles(),
            ];


            $token = generateToken($data['user'], $this->auth->getRoles());
            $data['token'] = $token;


            // TODO: make session.controller to generate auth token and return to user
            // $this->logger->info($status[0][1], );

            $this->setStatus(200, 'Login successful', [
                'ip'        => $this->auth->getIpAddress(),
                'userId'    => $this->auth->getUserId(),
            ]);
        }
        catch (Auth\InvalidEmailException $e) {

            $this->setStatus(400, 'Wrong login credentails', [
                'ip'        => $this->auth->getIpAddress(),
                'userId'    => $this->auth->getUserId(),
            ]);
        }
        catch (Auth\InvalidPasswordException $e) {
            $this->setStatus(400, 'Wrong login credentails', 'Wrong password');
        }
        catch (Auth\EmailNotVerifiedException $e) {
            $this->setStatus(401, 'Email not verified');
        }
        catch (Auth\TooManyRequestsException $e) {
            $this->setStatus(429, 'Too many requests');
        }

        return $res->withJson($data, $statusCode);
    }

    /**
     * [logout description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function logout(Request $req, Response $res) {

        $body   = $req->getParsedBody();

        try {
            $this->auth->logOutEverywhere();
            $this->auth->destroySession();
            session_destroy();
            $this->setStatus(200, 'Login successful');
        }
        catch (Auth\NotLoggedInException $e) {
            $this->setStatus(400, 'Wrong login credentails', 'Wrong email');
            // die('Not logged in');
        }

        return $res->withJson($this->resBody, $this->statusCode);
    }



    /**
     * [register description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function register(Request $req, Response $res) {

        $body = $req->getParsedBody();

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

            $this->setStatus(200, 'User registration was successful');
        }
        catch (Auth\InvalidEmailException $e) {
            $this->setStatus(400, 'Provided email was invalid');
        }
        catch (Auth\InvalidPasswordException $e) {
            $this->setStatus(400, 'Provided password was invalid');
        }
        catch (Auth\UserAlreadyExistsException $e) {
            $this->setStatus(409, 'That username is already taken');
        }
        catch (Auth\TooManyRequestsException $e) {
            $this->setStatus(429, 'Too many requests');
        }

        return $res->withJson($this->resBody, $this->statusCode);
    }


    // TODO: fully integrate resetPassword
    /**
     * [resetPassword description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function resetPassword(Request $req, Response $res) {

        $body = $req->getParsedBody();

        try {
            $this->auth->resetPassword($_POST['selector'], $_POST['token'], $_POST['password']);

            $this->setStatus(200, 'Password has been reset');

        }
        catch (Auth\InvalidSelectorTokenPairException $e) {
            $this->setStatus(400, 'Invalid token');
            // die('Invalid token');
        }
        catch (Auth\TokenExpiredException $e) {
            $this->setStatus(401, 'Token expired');
            // die('Token expired');
        }
        catch (Auth\ResetDisabledException $e) {
            $this->setStatus(403, 'Password reset is disabled');
            // die('Password reset is disabled');
        }
        catch (Auth\InvalidPasswordException $e) {
            $this->setStatus(400, 'Invalid password');
            // die('Invalid password');
        }
        catch (Auth\TooManyRequestsException $e) {
            $this->setStatus(429, 'Too many requests');
            // die('Too many requests');
        }

        return $res->withJson($this->resBody, $this->statusCode);
    }


    // TODO: fully integrate resendConfirmation
    /**
     * [resendConfirmation description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function resendConfirmation(Request $req, Response $res) {

        $body = $req->getParsedBody();


        try {
            $this->auth->resendConfirmationForEmail($_POST['email'], function ($selector, $token) {
                echo 'Send ' . $selector . ' and ' . $token . ' to the user (e.g. via email)';
            });

            $this->setStatus(200, 'The user may now respond to the confirmation request (usually by clicking a link)');
        }
        catch (Auth\ConfirmationRequestNotFound $e) {
            $this->setStatus(404, 'No earlier request found that could be re-sent');
            // die('No earlier request found that could be re-sent');
        }
        catch (Auth\TooManyRequestsException $e) {
            $this->setStatus(429, 'There have been too many requests -- try again later');
            // die('There have been too many requests -- try again later');
        }

        return $res->withJson($this->resBody, $this->statusCode);
    }


    // TODO: fully integrate verifyPasswordReset
    /**
     * [verifyPasswordReset description]
     * @param  Request  $req [description]
     * @param  Response $res [description]
     * @return [type]        [description]
     */
    public function verifyPasswordReset(Request $req, Response $res) {

        $body = $req->getParsedBody();


        try {
            $this->auth->canResetPasswordOrThrow($_GET['selector'], $_GET['token']);

            $this->setStatus(200, 'Please enter your new password');
        }
        catch (Auth\InvalidSelectorTokenPairException $e) {
            $this->setStatus(404, 'Invalid token');
            // die('Invalid token');
        }
        catch (Auth\TokenExpiredException $e) {
            $this->setStatus(429, 'Token expired');
            // die('Token expired');
        }
        catch (Auth\ResetDisabledException $e) {
            $this->setStatus(403, 'Password reset is disabled');
            // die('Password reset is disabled');
        }
        catch (Auth\TooManyRequestsException $e) {
            $this->setStatus(429, 'Too many requests');
            // die('Too many requests');
        }

        return $res->withJson($this->resBody, $this->statusCode);
    }


}
