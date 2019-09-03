<?php

use Opis\Database\Connection;
use Opis\Database\Database;

// SETUP PATH ALIASES
$ROOT_DIR = realpath(getcwd() . '/..');
define('ROOT_DIR', $ROOT_DIR);


// DEV LOAD ENVIRONMENT
$dotenv = Dotenv\Dotenv::create(ROOT_DIR);
// $dotenv->load();
$dotenv->overload();

$dotenv->required([
    'DB_TYPE',
    'DB_NAME',
    'DB_HOST',
    'DB_CONNECTION',
]);




$IS_DEV = env('ENV', 'production') !== 'production';
define('IS_DEV',    $IS_DEV);
define('IS_PROD',   !$IS_DEV);


// DEV RUN WHOOPS!
$whoops = new \Whoops\Run;
$whoops->prependHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


// SETUP DB CONNECTION
$connection = new Connection(
    env('DB_CONNECTION', 'sqlite:host=localhost;dbname=main'),
    'username',
    'password'
);

$db = new Database($connection);
