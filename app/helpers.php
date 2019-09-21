<?php

use \Firebase\JWT\JWT;



//  TIME HELPER FUNCITON(S)
// ===================================

function minutes(int $n=1)  :int { return $n *  60; }
function hours(int $n=1)    :int { return $n *  60 * minutes(); }
function days(int $n=1)     :int { return $n *  24 * hours(); }
function weeks(int $n=1)    :int { return $n *   7 * days(); }
function months(int $n=1)   :int { return $n *  30 * days(); }
function years(int $n=1)    :int { return $n * 365 * days(); }

function toMicrotime(int $time) :int {
    return $time * 1000;
}

function fromMicrotime(int $timestamp) :int {
    return floor($time / 1000);
}



//  PATH HELPER FUNCITON(S)
// ===================================

function buildPath() {
    $getRealpath = false;

    $parts    = func_get_args();

    $path     = implode('/', $parts);
    $path     = preg_replace('/\/+/', '/', $path);

    if ($getRealpath) {
        $path = realpath($path);
    }

    return $path;
}


//  ENVIRONMENT HELPER FUNCITON(S)
// ===================================

function env($key, $default=null) {
    $value = getenv($key);

    if (!$value && $default) {
        return $default;
    }

    if ($value === 'true') {
        return true;
    }

    if ($value === 'false') {
        return false;
    }

    return $value;
}



//  UTILITY HELPER FUNCITON(S)
// ===================================

function generateToken(array $subject=[], array $roles=[], int $nbf=0) {
    $secret = env('JWT_SECRET');

    $token = [];

    $now = time();

    $token['iss'] = env('APP_HOSTNAME');
    $token['exp'] = $now + hours(env('JWT_TTL', 24));
    $token['iat'] = $now;
    $token['nbf'] = $now + $nbf;
    $token['sub'] = $subject;
    $token['scope'] = $roles;

    $encoding = explode('|', env('JWT_ALGORITHM'));

    return JWT::encode($token, $secret, $encoding[0]);
}
