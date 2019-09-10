<?php


use Monolog\Logger;


return [
    'settings' => [
        'addContentLengthHeader'    => false, // Allow the web server to send the content-length header

        'displayErrorDetails'       => IS_DEV, // set to false in production

        // Monolog settings
        'logger' => [
            'name'  => env('LOGGER_NAME'),
            'path'  => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => Logger::DEBUG,
        ],
    ],
];
