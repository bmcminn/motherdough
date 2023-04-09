<?php

namespace App\Models;


use RedBeanPHP\Facade as R;

// TODO setup database sessions table; store createdAt, expiresAt, id

// @sauce: https://www.php.net/manual/en/function.session-cache-limiter.php
define('SESSION_CACHE_PRIVATE',             'private');
define('SESSION_CACHE_PUBLIC',              'public');
define('SESSION_CACHE_PRIVATE_NO_EXPIRE',   'private_no_expire');
define('SESSION_CACHE_NOCACHE',             'nocache');
define('SESSION_CACHE_NONE',                false);


class Session {

    public static function setup(array $options) : void {

        $options = array_replace_recursive([
            'cache_limiter' => false,
            'expires' => days(2),
            'session_name' => 'APP_SESSION_NAME',
        ], $options);

        ini_set('session.cookie_httponly', 1);

        session_cache_limiter($options['cache_limiter']);

        if (isset($options['session_name'])) {
            session_name($options['session_name']);
        }

        if (isset($options['path'])) {
            session_save_path($options['path']);
        }


        self::start();

        if (!self::get('expires')) {
            self::set('expires', now() + $options['expires']);
        }

    }


    public static function start() {
        session_start();
    }


    public static function destroy() {
        session_destroy();
    }


    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }


    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }


}
