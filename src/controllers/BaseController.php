<?php

namespace App\Controllers;

use Delight\Auth;
use Slim\Http\Request;
use Slim\Http\Response;


class BaseController {

    public $ctx;
    public $auth;
    public $logger;
    public $settings;


    public $statusCode  = 200;
    public $statusMsg   = '';

    public $resBody = [];


    public function __construct(\Slim\Container $ctx) {
        $this->ctx      = $ctx;
        $this->auth     = $ctx->get('auth');
        $this->logger   = $ctx->get('logger');
        $this->settings = $ctx->get('settings');
    }


    protected function setStatus(int $code, array $ctx = null, string $message, string $logMessage = null) {
        $this->statusCode   = $code;

        $this->resBody['success'] = $code < 400 ? true : false;
        $this->resBody['message'] = trim($message);

        $logMessage     = $logMessage ?? $message;
        $logMessage     = trim($logMessage);


        $ctx    = $ctx ?? [];

        $ctx['ipaddress']   = $_SERVER['REMOTE_ADDR'];
        $ctx['ipforwarded'] = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;


        // TODO: setup logging when certain $code values are met
        switch($code) {
            case 200:
                $this->logger->info($logMessage, $ctx);
                break;
            case 300:
            case 301:
            case 302:
            case 303:
            case 400:
            case 403:
                $this->logger->warning($logMessage, $ctx);
                break;
            case 200:
            case 500:
                $this->logger->error($logMessage, $ctx);
                break;
        }
    }


}
