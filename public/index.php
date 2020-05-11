<?php

use App\Object\View;
use Pecee\SimpleRouter\SimpleRouter as Router;


if (PHP_SAPI === 'cli-server') {

    set_time_limit(0);

    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];

    if (is_file($file)) {
        return false;
    }
}


require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/../src/app/bootstrap.php';


/**
 * Dispatch request to the routes script.
 */
// require route_path('api.php');
// require route_path('client.php');


Router::error(function($request, \Exception $exception) {

    return View::render('main');
    if ($exception instanceof NotFoundHttpException && $exception->getCode() === 404) {
        return View::render('main');
        // response()->redirect('/not-found');
    }

});


Router::start();

