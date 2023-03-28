<?php
namespace App\Middleware;

use App\Helpers\Config;
use App\Helpers\Logger;
use App\Helpers\Session;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

use RedBeanPHP\Facade as R;


class UserLoggedIn {
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $req PSR-7 request
     * @param  RequestHandler $next PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $req, RequestHandler $next) {
        $route          = '/' . getRoute($req)->getArgument('path');

        $userGuest      = empty($_SESSION['user_id']);

        $publicRoutes   = array_values(Config::get('public_routes'));

        $isPublicRoute  = in_array($route, $publicRoutes);

        if ($userGuest && !$isPublicRoute) {
            return redirect('/login');
        }

        $res = $next->handle($req);

        return $res;
    }
}
