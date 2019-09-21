<?php

namespace App\Dependencies;

use Slim\Container;


class CSRF {

    public function __invoke(Container $c) {
        return new \Slim\Csrf\Guard;
    }

}
