<?php

use App\Helpers\Config;
use App\Helpers\Hash;
use App\Helpers\Logger;
use App\Helpers\Template;
use App\Helpers\Validator;

use App\Models\Session;

use RedBeanPHP\Facade as R;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


define('HTTP_SUCCESS', 200);
define('HTTP_CREATED', 201);

define('HTTP_MOVED_PERMANENTLY', 301);
define('HTTP_MOVED_TEMPORARILY', 302);
define('HTTP_PERMANENT_REDIRECT', 302);

define('HTTP_BAD_REQUEST', 400);
define('HTTP_UNAUTHORIZED', 401);
define('HTTP_PAYMENT_REQUIRED', 402);
define('HTTP_FORBIDDEN', 403);
define('HTTP_NOT_FOUND', 404);
define('HTTP_RATE_LIMITED', 429);

define('HTTP_SERVER_ERROR', 500);


Config::setup([

    'company' => [
        'name'          => 'Company Name',
        'legalName'     => 'Company Name, LLC.',
        'phone'         => '555-555-1234',
        'email'         => 'email@company.name',
        'emailFrom'     => 'do-not-reply@company.name',
        'emailReply'    => 'replyto@company.name',

        'address' => [
            'street1'   => '123 Waffleton Way',
            'street2'   => 'Building 3, Suite 205',
            'city'      => 'Fauston',
            'state'     => 'AA',
            'zipcode'   => '55555',
        ],
    ],

    'app' => [
        'name' => 'MLSDB',
        'established' => 2023,
        'copyright' => 'Gbox Studios',
    ],

    'registration' => [
        'min_age' => 18,
    ],

    'paths' => [
        'cache_dir'     => path('/storage/cache'),
        'database_dir'  => path('/storage/database'),
        'database_file' => path('/storage/database/main.db'),
        'logs_dir'      => path('/storage/logs'),
        'sessions_dir'  => path('/storage/sessions'),
        'views_dir'     => path('/src/views'),
    ],

    'public_routes' => [
        // PUBLIC CONTENT PAGES
        'home'              => '/',
        'about'             => '/about',
        'privacy'           => '/privacy-policy',
        'terms'             => '/terms-of-use',

        // AUTH ROUTES
        'login'             => '/login',
        'logout'            => '/logout',
        'register'          => '/register',
        'passwordreset'     => '/password-reset',
        'verification'      => '/verification',

        // DASHBOARD ROUTES

    ],

    'emails' => [
        // TODO: convert these values to use the environment variables
        // NOTES: https://github.com/rnwood/smtp4dev/wiki/Configuring-Clients
        'smtp' => [
            'auth'      => false,
            'autotls'   => false,
            'debug'     => false,
            'enabled'   => true,
            'host'      => 'smtp4dev',
            'password'  => '',
            'port'      => 25,
            'secure'    => false,
            'username'  => '',
        ],
    ],


]);


[$year, $month, $day] = explode('-', date('Y-m-d'));

Config::set([
    'date' => [
        'year' => $year,
        'month' => $month,
        'day' => $day,
        'full' => date('Y-m-d'),
    ],
]);



// SETUP HASH UTILITY

Hash::setup();


// DOCUMENT VARIOUS FOLDER LOCATIONS

// TODO: adjust folder permissions and can test whether it works or not
// TODO: abstract this to its own postinstall script to scaffold out folder structure
foreach ([
    [ Config::get('paths.cache_dir'),       0666 ],
    [ Config::get('paths.database_dir'),    0666 ],
    [ Config::get('paths.logs_dir'),        0666 ],
    [ Config::get('paths.sessions_dir'),    0666 ],
    [ Config::get('paths.views_dir'),       0666 ],
] as $path) {
    [ $filepath, $permissions ] = $path;
    if (is_dir($filepath)) { continue; }
    mkdir($filepath, $permissions, true);
}


Logger::setup([
    'logs_path' => Config::get('paths.logs_dir'),
    'max_logs'  => 20,
]);


Session::setup([
    'path' => Config::get('paths.sessions_dir'),
]);


Template::setup([
    'model'     => Config::get(),
    'ext'       => '.twig',
    'cache_dir' => path(Config::get('paths.cache_dir') . '/views'),
    'views_dir' => Config::get('paths.views_dir'),
    'filters' => [
        'url' => 'url'
    ],
]);


R::setup('sqlite:' . Config::get('paths.database_file'));
R::useFeatureSet( 'novice/latest' );
