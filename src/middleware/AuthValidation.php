<?php

namespace App\Middleware;

use App\Middleware\ValidationMiddleware;
use Rakit\Validation\Validator;
use Slim\Http\Request;
use Slim\Http\Response;


class AuthValidation extends ValidationMiddleware {

    /**
     * @param  Request
     * @param  Response
     * @param  [type]
     * @return [type]
     */
    public function login(Request $req, Response $res, $next) {

        // sanitize all form data
        $body = $req->getParsedBody();

        $body = filter_var_array($body, [
            'email'     => FILTER_SANITIZE_EMAIL,
            'password'  => FILTER_SANITIZE_STRING,
        ]);

        // validate sanitized data
        $validator    = new Validator();

        $validation = $validator->make($body, [
            'email'     => 'required|email',
            'password'  => 'required|min:16',
        ]);

        $validation->validate();


        if ($validation->fails()) {
            $errors = $validation->errors();

            $msg = [ 'errors' => $errors->toArray() ];
            $this->logger->warning('login error', $msg);

            return $res->withStatus(403)->withJson($msg);
        }

        $res = $next($req, $res);

        // DISCUSS: could we bake route response validation into the post $next block?

        return $res;
    }



    /**
     * @param  Request
     * @param  Response
     * @param  [type]
     * @return [type]
     */
    public function register(Request $req, Response $res, $next) {

        // sanitize all form data
        $body = $req->getParsedBody();

        $body = filter_var_array($body, [
            'email'             => FILTER_SANITIZE_EMAIL,
            'password'          => FILTER_SANITIZE_STRING,
            'confirm_password'  => FILTER_SANITIZE_STRING,
        ]);

        // validate sanitized data
        $validator    = new Validator();

        $validation = $validator->make($body, [
            'email'             => 'required|email',
            'password'          => 'required|min:16',
            'confirm_password'  => 'required|same:password',
        ]);

        $validation->validate();


        if ($validation->fails()) {
            $errors = $validation->errors();

            $this->logger->warning('login error', [ $errors ]);

            return $res->withStatus(403)->withJson($errors);
        }


        $res = $next($req, $res);

        // DISCUSS: could we bake route response validation into the post $next block?

        return $res;
    }


    // logout
    // register
    // confirmation
    // deactivate


}
