<?php


define('DS', DIRECTORY_SEPARATOR);


function now() {
    return floor(microtime(true) * 1000);
}

function minutes() {
    return 1000 * 60;
}

function hours() {
    return minutes() * 60;
}

function days() {
    return hours() * 24;
}


function path(string $path) {
    return getcwd() . '/../' . trim($path, '/');
}


/**
 * { function_description }
 *
 * @param      string               $path    The path
 * @param      int                  $status  The status
 *
 * @throws     ErrorExeception      (description)
 *
 * @return     \Slim\Psr7\Response  ( description_of_the_return_value )
 */
use \Slim\Psr7\Response;

function redirect(string $path, int $status=302) : Response {
    if ($status < 300 || 399 < $status) {
        throw new ErrorExeception('redirect() $status must be a 3XX status code; ');
    }

    $res = new Response($status);
    return $res->withHeader('Location', $path);
}


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteContext;



function getRoute(Request $req) {
    $ctx = RouteContext::fromRequest($req);
    return $ctx->getRoute();
}


function jsonResponse($data, int $status = 200, $res = null) {
    $body = json_encode($data);

    $res = $res ?? new \Slim\Psr7\Response($status);

    $res->getBody()->write($body);

    return $res
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($status);
}


/**
 * Enter any number of comma separated arguments to compose a debug message to the PHP console
 */
function dbg() {

    $args = func_get_args();
    $msg = [];

    foreach ($args as $arg) {
        $prefix = '';
        switch(gettype($arg)) {
            case 'object':
            case 'array':
                $prefix = '[' . gettype($arg) . ']';
                $arg = json_encode($arg, JSON_PRETTY_PRINT);
                break;
            case 'boolean':
            case 'bool':
                $arg = $arg === true ? 'TRUE' : 'FALSE';
                break;
        }
        array_push($msg, $prefix . $arg);
    }

    file_put_contents('php://stdout', implode("\n", $msg) . "\n");
}



function uuid4() {
    return Ramsey\Uuid\Uuid::uuid4()->toString();
}
