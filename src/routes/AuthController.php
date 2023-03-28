<?php

use App\Helpers\Validator;

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

        $body = $req->getParsedBody();


        $validate = Validator::loginModel($body);


        $data = [
            'message' => 'login data accepted',
            'body' => $body,
        ];

        return jsonResponse($data);
    }


    public function logout(Request $req, Response $res) : Response {
        $res->getBody()->write('logout');
        return $res;
    }


    public function passwordReset(Request $req, Response $res) : Response {
        $res->getBody()->write('password-reset');
        return $res;
    }


    public function passwordVerify(Request $req, Response $res) : Response {
        $res->getBody()->write('password-verify');
        return $res;
    }

}


// $app->group
$app->group('/api/auth', function($api) {
    $api->post('/register',                 AuthController::class . ':register');
    $api->post('/login',                    AuthController::class . ':login');
    $api->post('/logout',                   AuthController::class . ':logout');
    $api->post('/password-reset',           AuthController::class . ':passwordReset');
    $api->get('/password-verify/:token',    AuthController::class . ':passwordVerify');
});
