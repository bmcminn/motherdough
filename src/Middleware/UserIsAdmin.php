<?php
namespace App\Middleware;

use App\Helpers\Logger;
use App\Models\Session;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use RedBeanPHP\Facade as R;


class UserIsAdmin {
    /**
     * Example middleware invokable class
     *
     * @param  ServerRequest  $req PSR-7 request
     * @param  RequestHandler $next PSR-15 request handler
     *
     * @return Response
     */
    public function __invoke(Request $req, RequestHandler $next) : Response {
        $res = $next->handle($req);

        // if (!User::isAdmin()) {
        //     $res
        // }

        return $res;
    }
}
