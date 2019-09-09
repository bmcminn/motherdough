<?php

declare(strict_types=1);


use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function(App $app) {


	$app->get('/hello/{name}', function (Request $req, Response $res, array $args) {
	    $name = $args['name'];
	    $res->getBody()->write("Hello, {$name}");
	    return $res;
	});

};