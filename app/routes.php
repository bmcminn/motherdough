<?php
declare(strict_types=1);


use App\Controllers\AuthController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;


return function (App $app) {

    // $app->get('/[{name}]', function (Request $req, Response $res) {
    //     // Sample log message
    //     $logger = $this->get('logger');
    //     $logger->info("Slim-Skeleton '/' route");

    //     // Render index view
    //     return $response->write('hello');
    // });

    $app->post('/auth/login', AuthController::class . ':login');

    // $app->group('/auth', function (Group $group) {
    //     // $group->post('/login', Controllers\AuthController::class . ':login');
    //     $group->post('/login', function(Request $req, Response $res) {
    //         return $res->getBody()->write('afsejkflesjflse');
    //     });
    // });


    $app->group('/api', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

};
