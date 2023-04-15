<?php

use App\Template;
use App\Middleware\UserLoggedIn;
use App\Helpers\Config;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response7;

// -----

class AppController {


    public function app (Request $req, Response $res) {
        $body = 'home';

        $filepath = path('/public/index.html');
        $body = file_get_contents($filepath);

        $routes = json_encode([
            'routes' => Config::get('public_routes'),
        ]);

        $src = <<<SRC
            <script>
                window.AppConfig = $routes
            </script>
        SRC;

        $body = preg_replace('/<!--\s*PHPINCLUDE\s*-->/i', trim($src), $body);

        $res->getBody()->write($body);

        return $res;
    }


}


$app->get('/{path:.*}', AppController::class . ':app')
    ->add(\App\Middleware\UserLoggedIn::class)
;
