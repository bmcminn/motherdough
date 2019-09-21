<?php

namespace App\Dependencies;

use Slim\Container;


class Auth {

    public function __invoke(Container $c) {
        return new \Delight\Auth\Auth($c->get('database'));
    }

}
