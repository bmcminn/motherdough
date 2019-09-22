<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Delight\Auth;
use Slim\Http\Request;
use Slim\Http\Response;


class BaseController {

    public $ctx;
    public $auth;
    public $logger;
    public $settings;


    public function __construct(\Slim\Container $ctx) {
        $this->ctx      = $ctx;
        $this->auth     = $ctx->get('auth');
        $this->logger   = $ctx->get('logger');
        $this->settings = $ctx->get('settings');
    }

}
