<?php

namespace App\Middleware;


class ValidationMiddleware {

    public $ctx;
    public $auth;
    public $logger;
    public $settings;
    // public $sanitizer;


    public function __construct(\Slim\Container $ctx) {
        $this->ctx          = $ctx;
        $this->auth         = $ctx->get('auth');
        $this->logger       = $ctx->get('logger');
        $this->settings     = $ctx->get('settings');
        // $this->sanitizer    = $ctx->get('sanitizer');
    }

}
