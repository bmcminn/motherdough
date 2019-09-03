<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $logsDir = env('LOGGER_DIRECTORY');

    $containerBuilder->addDefinitions([
        'settings' => [
            'displayErrorDetails' => IS_DEV, // Should be set to false in production
            'logger' => [
                'name'  => 'logs',
                'path'  => $logsDir ? ROOT_DIR . $logsDir : 'php://stdout',
                'level' => Logger::DEBUG,
            ],
        ],
    ]);
};
