<?php

use Delight\Db;


// SETUP PATH ALIASES
// $ROOT_DIR = realpath(getcwd() . '/..');
define('ROOT_DIR', buildPath(getcwd(), '/..'));
define('DATA_DIR', buildPath(ROOT_DIR, '/data'));


// DEV LOAD ENVIRONMENT
$dotenv = Dotenv\Dotenv::create(ROOT_DIR);
// $dotenv->load();
$dotenv->overload();

$dotenv->required([
	'APP_TIMEZONE',
    'DB_DATABASE',
    'DB_HOSTNAME',
    // 'DB_PASSWORD',
    // 'DB_USERNAME',
]);


// DEFINE ENVIRONMENT CONSTANTS
$IS_DEV = env('ENV', 'production') !== 'production';
define('IS_DEV',  $IS_DEV);
define('IS_PROD', !$IS_DEV);


// SET SERVER CONFIGS
date_default_timezone_set(env('APP_TIMEZONE'));

// DEV RUN WHOOPS!
$whoops = new \Whoops\Run;
$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


// // SETUP DB CONNECTION
// $connection = new Connection(
//     env('DB_CONNECTION', 'sqlite:host=localhost;dbname=main'),
//     'username',
//     'password'
// );

// $db = new Database($connection);


$DB_DATABASE = env('DB_DATABASE', 'sqlite');
$DB_PATH = buildPath(DATA_DIR, env('DB_FILEPATH', ''));

$db = Db\PdoDatabase::fromDsn(
    new Db\PdoDsn(
        "{$DB_DATABASE}:{$DB_PATH}",
        env('DB_USERNAME', null),
        env('DB_PASSWORD', null)
    )
);

