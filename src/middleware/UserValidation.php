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

        // TODO: https://github.com/delight-im/PHP-Auth#user-id

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


}
