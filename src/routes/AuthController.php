<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use RedBeanPHP\Facade as R;

// -----

class AuthController {


    public function __invoke() {

    }


    public function register(Request $req, Response $res) : Response {
        return $res->getBody()->write('register');
        // return $this->res->send('register');
    }


    public function login(Request $req, Response $res) : Response {
        // $this here refer to App instance
        // $config = $this->config['any.config'];
        // $file   = $this->request->file('a_file');
        $res->getBody()->write('login');
        return $res;
    }


    public function logout(Request $req, Response $res) : Response {
        $res->getBody()->write('logout');
        return $res;
    }


    public function passwordReset(Request $req, Response $res) : Response {
        $res->getBody()->write('password-reset');
        return $res;
    }


    public function delete(Request $req, Response $res) : Response {
        $res->getBody()->write('delete');
        return $res;
    }

}


// $app->group
$app->group('/api/auth', function($api) {
    $api->post('/register',                 AuthController::class . ':register');
    $api->post('/login',                    AuthController::class . ':login');
    $api->post('/logout',                   AuthController::class . ':logout');
    $api->get('/password-verify/:token',    AuthController::class . ':passwordReset');
    $api->delete('/delete',                 AuthController::class . ':delete');
});
