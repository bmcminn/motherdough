<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;


return function(App $app) {


    $app->get('/', function($req, $res) {

        $data = [
            'message' => 'api base route!',
        ];

        return $res->withJson($data);
    });

};
