<?php

use App\Template;
use App\Middleware\UserLoggedIn;


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response7;

// -----

class AppController {


    public function app (Request $req, Response $res) {
        $body = 'home';

        $body = file_get_contents(__DIR__ . '/../../public/index.html');
        // $body = Template::render('app');
        $res->getBody()->write($body);

        return $res;
    }


}
// $app->get('/', function() {
//     return 'render homepage';
// })->auth();


$app->get('/{path:.*}', AppController::class . ':app')
    ->add(App\Middleware\UserLoggedIn::class)
;


// $app->get('/{path:.*}', function (Request $req, Response $res) {
//     $model = ['name' => 'thing'];

//     $body = file_get_contents(__DIR__ . '/../public/index.html');
//     // $body = Template::render('app');
//     // $body = 'wefsjekl';
//     $res->getBody()->write($body);

//     return $res;
// })
//     ->add(App\Middleware\UserLoggedIn::class)
// ;
