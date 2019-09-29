<?php

namespace App\Dependencies;

use Slim\Container;


class AuthJwt {

    public function __invoke(Container $c) {

        return new \Tuupola\Middleware\JwtAuthentication([
            'algorithm' => explode('|', env('JWT_ALGORITHM')),
            'secure'    => $c->get('settings')['isProduction'],
            'logger'    => $c->get('logger'),
            'path'      => '/api',
            'secret'    => env('JWT_SECRET'),
            'error' => function ($res, $e) {
                $data = [];
                $data['status']  = 'error';
                $data['message'] = $e['message'];

                return $res->withJson($data, 401);
            }
        ]);

    }

}
