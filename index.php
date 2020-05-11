<?php

use App\Logger;
use Pecee\SimpleRouter\SimpleRouter as Router;
use RedBeanPHP\Facade as R;


if (PHP_SAPI === 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];

    if (is_file($file)) { return false; }
}


require __DIR__ . '/vendor/autoload.php';



//
// SETUP LOGGER
//

$today = date('Y-m-d');

Logger::config([
    'name'      => 'events',
    'numLogs'   => is_prod() ? env('NUM_LOGS', 0) : 0,
    'logTarget' => storage_path("logs/{$today}.dev.log"),
]);



//
// SETUP ORM INSTANCE
//

require resource_path('lib/Redbean/rb-sqlite.php');

// define('REDBEAN_MODEL_PREFIX', '');
R::setup('sqlite:' . storage_path('data/dbfile.db'));
// R::debug(is_dev());

require_dir(src_path('models'));



//
// INIT ROUTES
//

Router::get('/', function() {

    return "sefsjekl";

});




// https://www.instagram.com/p/B_wOfqWoZnp/

Router::post('/auth/user',   '\App\Controllers\UserController@register');
Router::post('/auth/login',  '\App\Controllers\UserController@login');










Router::start();


R::close();