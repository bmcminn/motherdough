<?php

namespace App\Helpers;


use RedBeanPHP\Facade as R;

// TODO setup database sessions table; store createdAt, expiresAt, id


class Session {

    public static function setup(array $options) : void {

        $options = array_replace_recursive([
            'cache_limit' => false,
            'expires' => days(2),
        ], $options);

        ini_set('session.cookie_httponly', 1);

        session_cache_limiter($options['cache_limit']);

        if (isset($options['path'])) {
            session_save_path($options['path']);
        }

        session_start();

        if (!self::get('expires')) {
            self::set('expires', now() + $options['expires']);
        }

    }


    public static function destroy() {
        session_destroy();
    }


    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }


    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }


}
