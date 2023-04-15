<?php

use App\Helpers\Config;
use App\Helpers\Email;
use App\Helpers\Hash;
use App\Helpers\Validator;

use App\Models\Session;
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
            [ 'email',              'required|email|unique:user,email',   'email' ],
            // TODO: password, add not_invalid rule to check against knlown compromised passwords
            [ 'password',           'required|min:8',               'htmlspecialchars' ],
            [ 'password_confirm',   'required|same:password',       'htmlspecialchars' ],
            [ 'dateofbirth',        "required|min_age:{$minAge}",   'htmlspecialchars' ],
        ]);

        if (isset($data['messages'])) {
            // URGENT: need to stealth/alias unique email error message
            return jsonResponse($data, 400);
        }

        // create user
        $user = User::create($data);

        // send verification email
        // TODO: finish integrating email verification email

        $token = generateOTP();

        Session::set('token', $token);

        $model = [
            'user'  => $user,
            'token' => $token,
        ];

        Email::sendVerificationEmail($model);

        return jsonResponse([
            'message' => 'success',
        ]);
    }


    public function login(Request $req, Response $res) : Response {

        $body = $req->getParsedBody();

        // $validate = Validator::loginModel($body);
        $params = Validator::validate($body, [
            [ 'email',      'required',         'email' ],
            [ 'password',   'required|min:8',   'htmlspecialchars' ],
        ]);

        $data = [];

        $user = User::findByEmail($params['email']);

        $data['user']   = $user->export();
        $data['debug']  = $params;

        // capture the IP address of the user that set the session for future validation
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        Session::set('ip_hash',     Hash::md5($ipAddress));
        Session::set('ip_hash_raw', $ipAddress);

        // $model = Config::get();
        $model['user']  = $user;
        $model['otp'] = generateOTP(6, OTP_ALPHANUMERIC);

        Email::sendLoginOTP($model);

        return jsonResponse($data);
    }


    public function otpVerify(Request $req, Response $res) : Response {

        $res->getBody()->write('logout');
        return $res;

    }


    public function logout(Request $req, Response $res) : Response {
        Session::destroy();

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
    $api->post('/login/otp-verify',         AuthController::class . ':otpVerify');
    $api->post('/logout',                   AuthController::class . ':logout');
    $api->post('/password-reset',           AuthController::class . ':passwordReset');
    $api->get('/password-verify/:token',    AuthController::class . ':passwordVerify');
});
