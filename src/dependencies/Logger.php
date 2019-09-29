<?php

namespace App\Dependencies;

use Slim\Container;


class Logger {

    public function __invoke(Container $c) {
        $loggerSettings = $c->get('settings')['logger'];

        $logger = new \Monolog\Logger($loggerSettings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\RotatingFileHandler(
            $loggerSettings['path'],
            env('LOGGER_MAX_FILES', 0),
            $loggerSettings['level']
        ));

        return $logger;
    }

}




