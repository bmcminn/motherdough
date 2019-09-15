<?php
declare(strict_types=1);


use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class HomeController
{
   protected $container;

   // constructor receives container instance
   public function __construct(ContainerInterface $c) {
       $this->container = $c;
   }


   public function login(Request $req, Response $res, $args) {

        $auth   = $this->container->get('auth.controller');
        $logger = $this->container->get('logger');

        $body = $req->getParsedBody();

        $data = [];
        $data['message'] = 'auth base route!';

        if (IS_DEV) {
            $data['req'] = $body;
        }

        // Zero (0) means success, any non-zero exit status is an error
        $status = [
            0 => [ 200, 'Login successful' ],
            1 => [ 400, 'Wrong login credentails', 'Wrong email' ],
            2 => [ 400, 'Wrong login credentails', 'Wrong password' ],
            3 => [ 401, 'Email not verified' ],
            4 => [ 429, 'Too many requests' ],
        ];


        try {
            $auth->login($body['email'], $body['password']);

            $data['user'] = [
                'id'        => $auth->getUserId(),
                'roles'     => $auth->getRoles(),
            ];


            $token = generateToken($data['user'], $auth->getRoles());


            $data['token'] = $token;


            // TODO: make session.controller to generate auth token and return to user

            $logger->info($status[0][1], [
                'ip'        => $auth->getIpAddress(),
                'userId'    => $auth->getUserId(),
            ]);

            $statusCode         = $status[0][0];
            $data['message']    = $status[0][1];
        }

        catch (\Delight\Auth\InvalidEmailException $e) {
            $statusCode         = $status[1][0];
            $data['message']    = $status[1][1];

            $logger->warning($status[1][1], [
                'ip'        => $auth->getIpAddress(),
                'userId'    => $auth->getUserId(),
            ]);
        }

        catch (\Delight\Auth\InvalidPasswordException $e) {
            $statusCode         = $status[2][0];
            $data['message']    = $status[2][1];
        }

        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            $statusCode         = $status[3][0];
            $data['message']    = $status[3][1];
        }

        catch (\Delight\Auth\TooManyRequestsException $e) {
            $statusCode         = $status[4][0];
            $data['message']    = $status[4][1];
        }


        return $res->withJson($data, $statusCode);

        // your code
        // to access items in the container... $this->container->get('');
        return $res;
   }


   public function contact(Request $req, Response $res, $args) {
        // your code
        // to access items in the container... $this->container->get('');
        return $res;
   }
}
