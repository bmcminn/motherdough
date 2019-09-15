<?php
declare(strict_types=1);


use Delight\Db;
use DI\ContainerBuilder;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Tuupola\Middleware\JwtAuthentication;


return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([

        'logger' => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $handler = new RotatingFileHandler($loggerSettings['path'], intval(env('LOGGER_MAX_FILES', 0)), $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },


        'csrf' => function (ContainerInterface $c) {
            return new \Slim\Csrf\Guard;
        },


        'database' => function (ContainerInterface $c) {
            $DB_DATABASE = env('DB_DATABASE', 'sqlite');
            $DB_PATH     = buildPath(DATA_DIR, env('DB_FILEPATH', ''));

            $db = Db\PdoDatabase::fromDsn(
                new Db\PdoDsn(
                    "{$DB_DATABASE}:{$DB_PATH}",
                    env('DB_USERNAME', null),
                    env('DB_PASSWORD', null)
                )
            );

            return $db;
        },


        'auth.controller' => function (ContainerInterface $c) {
            $db = $c->get('database');
            new \Delight\Auth\Auth($db);
        },

    ]);


};
