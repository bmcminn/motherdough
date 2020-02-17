<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;


class DebugMiddleware {

    public function __invoke(Request $req, Response $res, $next) {
        $res = $next($req, $res);

        $IS_JSON = $_SERVER['CONTENT_TYPE'] === 'application/json';

        // $data['req'] = $req->getParsedBody();


        if (env('local') && $IS_JSON) {

            $body = $res->getBody()->__toString();
            $body = json_decode($body);

            $debug = (object) [];

            $debug->headers = [
                'Accept'            => $_SERVER['HTTP_ACCEPT'] ?? null,
                'Content-Length'    => $_SERVER['CONTENT_LENGTH'] ?? null,
                'Content-Type'      => $_SERVER['CONTENT_TYPE'] ?? null,
                'Cookie'            => $_SERVER['HTTP_COOKIE'] ?? null,
                'Host'              => $_SERVER['HTTP_HOST'] ?? null,
                'User-Agent'        => $_SERVER['HTTP_USER_AGENT'] ?? null,
            ];

            $debug->isJson       = $IS_JSON;
            $debug->method       = $_SERVER['REQUEST_METHOD'] ?? null;
            $debug->origin       = $_SERVER['HTTP_ORIGIN'] ?? null;
            $debug->requestTime  = $_SERVER['REQUEST_TIME'] ?? 0;

            $protocol       = $_SERVER['SERVER_PROTOCOL'] ?? null;
            $hostname       = $_SERVER['HTTP_HOST'] ?? null;
            $pathname       = $_SERVER['REQUEST_URI'] ?? null;

            $debug->url     = sprintf("%s%s%s", $protocol, $hostname, $pathname);
            $debug->host    = $hostname;
            $debug->path    = $pathname;

            $body->debug = $debug;
        }

        return $res->withJson($body);
    }

}
