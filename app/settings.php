<?php
declare(strict_types=1);


use DI\ContainerBuilder;
use Monolog\Logger;


return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => IS_DEV, // Should be set to false in production
            'logger' => [
                'name'  => 'api',
                'path'  => env('docker', false) ? 'php://stdout' : ROOT_DIR . '/logs/app.log',
                'level' => Logger::DEBUG,
            ],
        ],
    ]);
};
