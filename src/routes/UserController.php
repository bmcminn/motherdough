<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use RedBeanPHP\Facade as R;

// -----

class UserController {


    public function getAll(Request $req, Response $res) : Response {

        return $res->send('user::getAll');

    }


    public function create(Request $req, Response $res) : Response {
        // $this here refer to App instance
        // $config = $this->config['any.config'];
        // $file   = $this->request->file('a_file');
        return $res->send('user::create');
    }


    public function read(Request $req, Response $res) : Response {
        return $res->send('user::read');
    }


    public function update(Request $req, Response $res) : Response {
        return $res->send('user::update');
    }


    public function delete(Request $req, Response $res) : Response {
        return $res->send('user::delete');
    }

}


// $app->group
$app->group('/api', function($api) {
    $api->get('/user',          UserController::class . ':getAll');
    $api->post('/user',         UserController::class . ':create');
    $api->get('/user/:id',      UserController::class . ':read');
    $api->put('/user/:id',      UserController::class . ':update');
    $api->delete('/user/:id',   UserController::class . ':delete');
})
    // ->add(auth())
    // ->isAdmin()
    ;
