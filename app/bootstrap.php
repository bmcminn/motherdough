<?php

use Delight\Db;


// SETUP PATH ALIASES
// ----------------------------------------------------------------------

define('ROOT_DIR', buildPath(getcwd(), ''));
define('DATA_DIR', buildPath(ROOT_DIR, '/data'));
define('ROUTES_DIR', buildPath(ROOT_DIR, '/src/routes'));
define('MIDDLEWARE_DIR', buildPath(ROOT_DIR, '/src/middleware'));


// DEV LOAD ENVIRONMENT
// ----------------------------------------------------------------------

$dotenv = Dotenv\Dotenv::create(ROOT_DIR);
// $dotenv->load();
$dotenv->overload();

$dotenv->required([
    'APP_ENV',
    'APP_TIMEZONE',
    'APP_TITLE',
    'DB_DATABASE',
    'DB_HOSTNAME',
]);



// DEFINE ENVIRONMENT CONSTANTS
// ----------------------------------------------------------------------

$IS_DEV = env('APP_ENV') !== 'production';

define('IS_DEV',  $IS_DEV);
define('IS_PROD', !$IS_DEV);



// SET SERVER CONFIGS
// ----------------------------------------------------------------------

date_default_timezone_set(env('APP_TIMEZONE'));



// DEV RUN WHOOPS!
// ----------------------------------------------------------------------

$whoops = new \Whoops\Run;
$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
