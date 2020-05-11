<?php

use App\Logger;
use App\View;
use Pecee\SimpleRouter\SimpleRouter as Router;
use RedBeanPHP\Facade as R;



if (PHP_SAPI === 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];

    if (is_file($file)) { return false; }
}



require __DIR__ . '/vendor/autoload.php';



//
// GET ENVIRONMENT CONFIGS
//

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();



//
// SETUP ERROR HANDLER(S)
//
$whoops = new \Whoops\Run;
// TODO: research Whoops JsonResponseHandler
// $whoops->pushHandler(new \Whoops\Handler\JsonResponseHandler);
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();



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
// SETUP VIEW INSTANCE
//

View::config(
	resource_path('views'),
	storage_path('views')
);



//
// SETUP ORM INSTANCE
//

require resource_path('lib/Redbean/rb-sqlite.php');

// define('REDBEAN_MODEL_PREFIX', '');
R::setup('sqlite:' . storage_path('data/dbfile.db'));
// R::debug(is_dev());

require_dir(src_path('api/Models'));



//
// INIT ROUTES/ROUTER
//

require src_path('api/routes.php');


Router::start();



//
// CLEANUP AFTER OURSELVES
//

R::close();