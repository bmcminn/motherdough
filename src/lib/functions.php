<?php


if (!defined('DS')) { define('DS', DIRECTORY_SEPARATOR); }


function env($key, $default=null) {
    $value = $_ENV[$key] ?? null;
    // $value = getenv($key);

    if (!$value && $default) {
        return $default;
    }

    if ($value === 'true') {
        return true;
    }

    if ($value === 'false') {
        return false;
    }

    return $value;
}


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
        throw new Error("redirect(\$path, \$status = $status) \$status must be a 3XX status code;");
    }

    $res = new Response($status);
    return $res->withHeader('Location', $path);
}


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;


function getRoute(Request $req) {
    $ctx = Slim\Routing\RouteContext::fromRequest($req);
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



define('OTP_ALPHANUMERIC',      00000001);
define('OTP_ALPHA',             00000010);
define('OTP_NUMERIC',           00000100);
define('OTP_MIXED_CASE',        00001000);
// define('OTP_ALLOW_PUNCTUATION', 00001000);

function generateOTP(int $length, int $flags = 0, int $charRange = null) {

    $length     = clamp($length, 2, 256);
    $chars      = "0123456789ABCDEFGHIKLMNOPQRSTUVWXYZ_-";
    $charRange  = $charRange ?? strlen($chars);
    $flags      = $flags ?? OTP_ALPHANUMERIC;
    $start      = $flags & OTP_ALPHA    ? 10 : 0;
    $end        = $flags & OTP_NUMERIC  ? 10 : $charRange;

    $charRange  = clamp($charRange, 2, $end);

    $otp = '';

    for ($i=0; $i < $length; $i++) {
        $ci = rand($start, $end - 1);
        $char = $chars[$ci];

        if ($flags & OTP_MIXED_CASE) {
            $char = !!rand(0,1) ? strtolower($char) : $char;
        }

        $otp .= $char;
    }

    return $otp;
}



/**
 * { function_description }
 *
 * @param      int    $value  The value
 * @param      int    $a      { parameter_description }
 * @param      int    $b      { parameter_description }
 *
 * @throws     Error  (description)
 *
 * @return     int    ( description_of_the_return_value )
 */
function clamp( $value, $a, $b) {
    if ($b < $a) {
        throw new Error("clamp(\$a = $a, \$b = $b): \$b cannot be less than \$a.");
    }

    if ($value < $a) { return $a; }
    if ($value > $b) { return $b; }

    return $value;
}
