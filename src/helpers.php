<?php

use App\Logger;
use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;
use Rakit\Validation\Validator;


/**
 * Get url for a route by using either name/alias, class or method name.
 *
 * The name parameter supports the following values:
 * - Route name
 * - Controller/resource name (with or without method)
 * - Controller class name
 *
 * When searching for controller/resource by name, you can use this syntax "route.name@method".
 * You can also use the same syntax when searching for a specific controller-class "MyController@home".
 * If no arguments is specified, it will return the url for the current loaded route.
 *
 * @param string|null $name
 * @param string|array|null $parameters
 * @param array|null $getParams
 * @return \Pecee\Http\Url
 * @throws \InvalidArgumentException
 */
function url(?string $name = null, $parameters = null, ?array $getParams = null) :Url {
    return Router::getUrl($name, $parameters, $getParams);
}

/**
 * @return \Pecee\Http\Response
 */
function response() :Response {
    return Router::response();
}

/**
 * @return \Pecee\Http\Request
 */
function request() :Request {
    return Router::request();
}

/**
 * Get input class
 * @param string|null $index Parameter index name
 * @param string|null $defaultValue Default return value
 * @param array ...$methods Default methods
 * @return \Pecee\Http\Input\InputHandler|array|string|null
 */
function input($index = null, $defaultValue = null, ...$methods) {
    if ($index !== null) {
        return request()->getInputHandler()->value($index, $defaultValue, ...$methods);
    }

    return request()->getInputHandler();
}

/**
 * @param string $url
 * @param int|null $code
 */
function redirect(string $url, ?int $code = null) :void {
    if ($code !== null) {
        response()->httpCode($code);
    }

    response()->redirect($url);
}

/**
 * Get current csrf-token
 * @return string|null
 */
function csrf_token() :?string {
    $baseVerifier = Router::router()->getCsrfVerifier();

    if ($baseVerifier !== null) {
        return $baseVerifier->getTokenProvider()->getToken();
    }

    return null;
}


function base_path($path='') {
    return __DIR__ . "/../{$path}";
}

function storage_path($path='') { return base_path("storage/{$path}"); }
function resource_path($path='') { return base_path("resources/{$path}"); }
function src_path($path='') { return base_path("src/{$path}"); }




if (!function_exists('throw_when')) {
    function throw_when(bool $fails, string $message, string $exception = Exception::class) {
        if (!$fails) { return; }

        throw new $exception($message);
    }
}


if (!function_exists('env')) {
    function env(string $key, $default = null) {
        $value = getenv($key);

        throw_when(!$key and is_null($default), "{$key} is not a defined .env variable and has no default value." );

        return $value or $default;
    }
}


if (!function_exists('is_prod')) {
    function is_prod() {
        return env('ENV', true) ? true : false;
    }
}


if (!function_exists('is_dev')) {
    function is_dev() {
        return !env('ENV', false) ? true : false;
    }
}






function validate($data, $config) {

    $validator = new Validator;

    // $validation = $validator->validate($_POST + $_FILES, [
    $validation = $validator->validate($data, $config);

    if ($validation->fails()) {
        return $validation->errors();
    }

    return [];

}


function only($data, array $list) :array {

    $res = [];

    foreach ($list as $i => $key) {
        if ($data[$key]) {
            $res[$key] = $data[$key];
        }
    }

    return $res;
}


function except($data, $list=[]) :array {

    foreach ($list as $i => $key) {
        if ($data[$key]) {
            unset($data[$key]);
        }
    }

    return $data;
}


function has($data, $list=[]) :array {
    foreach ($list as $i => $key) {
        if (!$data[$key]) {
            return false;
        }
    }

    return true;
}


function hash_password($pass) :string {
    return password_hash($pass, PASSWORD_ARGON2I);
}


function rehash_password($hash) :string {
    return password_needs_rehash($hash, PASSWORD_ARGON2I);
}



function load_models($dir) {

    $models = scandir($dir);

    foreach ($models as $filepath) {
        if ($filepath === '.' || $filepath === '..') { continue; }

        $filepath = $dir. '/' . $filepath;

        if (file_exists($filepath)) {
            require_once($filepath);
        }
    }

}