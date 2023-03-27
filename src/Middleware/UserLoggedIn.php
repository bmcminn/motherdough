<?php
namespace App\Middleware;

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
        $route = '/' . getRoute($req)->getArgument('path');

        $userGuest      = empty($_SESSION['user_id']);
        $isLoginPage    = in_array($route, [ '/login' ]);

        if ($userGuest && !$isLoginPage) {
            return redirect('/login');
        }

        $res = $next->handle($req);

        return $res;
    }
}
