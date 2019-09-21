<?php

namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;


class DebugMiddleware {

    public function __invoke(Request $req, Response $res, $next) {
        $res = $next($req, $res);

        $IS_JSON = $_SERVER['CONTENT_TYPE'] === 'application/json';

        $data['req'] = $req->getParsedBody();

        $body = $res->getBody()->__toString();
        $body = json_decode($body);

        if ($IS_JSON) {

            $body->debug_headers = [
                'Accept'            => $_SERVER['HTTP_ACCEPT'] ?? null,
                'Content-Length'    => $_SERVER['CONTENT_LENGTH'] ?? null,
                'Content-Type'      => $_SERVER['CONTENT_TYPE'] ?? null,
                'Cookie'            => $_SERVER['HTTP_COOKIE'] ?? null,
                'Host'              => $_SERVER['HTTP_HOST'] ?? null,
                'User-Agent'        => $_SERVER['HTTP_USER_AGENT'] ?? null,
            ];

            $body->debug_json         = $IS_JSON;
            $body->debug_method       = $_SERVER['REQUEST_METHOD'] ?? null;
            $body->debug_origin       = $_SERVER['HTTP_ORIGIN'] ?? null;
            $body->debug_requestTime  = $_SERVER['REQUEST_TIME'] ?? 0;

            $url_path   = $_SERVER['REQUEST_URI'] ?? null;
            $url_host   = $_SERVER['HTTP_HOST'] ?? null;

            $body->debug_url = "{$url_host}{$url_path}";
        }

        return $res->withJson($body);
    }

}
