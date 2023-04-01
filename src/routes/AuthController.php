<?php

use App\Helpers\Config;
use App\Helpers\Validator;
use App\Models\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use RedBeanPHP\Facade as R;

// -----

class AuthController {


    public function __invoke() {

    }


    public function register(Request $req, Response $res) : Response {

        $body = $req->getParsedBody();

        $minAge = Config::get('registration.min_age');

        $data = Validator::validate($body, [
            [ 'email',              'required|unique:user,email',   'email' ],
            // TODO: password, add not_invalid rule to check against known compromised passwords
            [ 'password',           'required|min:8',               FILTER_SANITIZE_STRING ],
            [ 'password_confirm',   'required|same:password',       FILTER_SANITIZE_STRING ],
            [ 'dateofbirth',        "required|min_age:{$minAge}",   FILTER_SANITIZE_STRING ],
        ]);

        if (isset($data['messages'])) {
            // URGENT: need to stealth/alias unique email error message
            return jsonResponse($data, 400);
        }

        // create user
        $user = User::create($data);

        // send verification email
        // TODO: finish integrating email verification email
        Email::sendVerificationEmail(Token::generateSHA512());


        return jsonResponse($data);
    }


    public function login(Request $req, Response $res) : Response {
        // $this here refer to App instance
        // $config = $this->config['any.config'];
        // $file   = $this->request->file('a_file');

        $body = $req->getParsedBody();

        // $validate = Validator::loginModel($body);
        $data = Validator::validate($body, [
            [ 'email',      'required',         'email' ],
            [ 'password',   'required|min:8',   FILTER_SANITIZE_STRING ],
        ]);

        $data['debug'] = $body;
        $data['message'] = $success;

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
