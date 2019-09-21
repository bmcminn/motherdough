<?php


use Monolog\Logger;


return [
    'settings' => [

        'isProduction'      => IS_PROD,
        'isDev'             => IS_DEV,

        'addContentLengthHeader'    => false, // Allow the web server to send the content-length header

        'displayErrorDetails'       => IS_DEV, // set to false in production

        // Monolog settings
        'logger' => [
            'name'  => env('LOGGER_NAME'),
            'path'  => isset($_ENV['docker']) ? 'php://stdout' : ROOT_DIR . '/logs/app.log',
            'level' => Logger::DEBUG,
        ],
    ],
];
