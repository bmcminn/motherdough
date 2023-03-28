<?php

use App\Helpers\Config;
use App\Helpers\Hash;
use App\Helpers\Logger;
use App\Helpers\Session;
use App\Helpers\Template;


use RedBeanPHP\Facade as R;


Config::setup([
    'app' => [
        'name' => 'MLSDB',
        'established' => 2023,
        'copyright' => 'Gbox Studios',
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
        'home' => '/',
        'about' => '/about',
        'privacy' => '/privacy-policy',
        'terms' => '/terms-of-use',
        'login' => '/login',
        'logout' => '/logout',
        'forgotpassword' => '/forgot-password',
    ],

]);


// SETUP HASH UTILITY

Hash::setup();



// DOCUMENT VARIOUS FOLDER LOCATIONS

// TODO: adjust folder permissions and can test whether it works or not
foreach ([
    [ Config::get('paths.cache_dir'),       0666 ],
    [ Config::get('paths.database_dir'),    0666 ],
    [ Config::get('paths.logs_dir'),        0666 ],
    [ Config::get('paths.sessions_dir'),    0666 ],
    [ Config::get('paths.views_dir'),       0666 ],
] as $path) {
    if (is_dir($path[0])) { continue; }
    mkdir($path[0], $path[1], true);
}



Logger::setup([
    'logs_path' => Config::get('paths.logs_dir'),
    'max_logs' => 20,
]);



Session::setup([
    'path' => Config::get('paths.sessions_dir'),
]);



Template::setup([
    'model' => Config::get(),
    'ext'   => '.twig',
    'cache_dir' => path(Config::get('paths.cache_dir') . '/views'),
    'views_dir' => Config::get('paths.views_dir'),
]);



R::setup('sqlite:' . Config::get('paths.database_file'));
// R::useFeatureSet(Config::ORM_FEATURES);
