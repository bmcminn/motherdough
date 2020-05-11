<?php

use App\Logger;
use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Url;
use Pecee\Http\Response;
use Pecee\Http\Request;


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



/**
 * Valdation macro for Rakit Validator
 * @sauce  https://github.com/rakit/validation
 * @param  array    $data   Associative array of values to validate
 * @param  array    $config Collection of parameters and their validation controls per the Rakit Validation documentation
 * @return array            If errors, returns list of errors; Else empty array
 */
function validate($data, $config) {
    $validator = new \Rakit\Validation\Validator;

    // $validation = $validator->validate($_POST + $_FILES, [
    $validation = $validator->validate($data, $config);

    if ($validation->fails()) {
        return $validation->errors();
    }

    return [];
}



/**
 * Filters an associative array to only have the data you want
 * @param  array    $data   Associative array of data to filter
 * @param  array    $list   List of properties to be captured
 * @return array            Filtered list of desired properties
 */
function only($data, array $list) :array {
    $res = [];

    foreach ($list as $i => $key) {
        if ($data[$key]) {
            $res[$key] = $data[$key];
        }
    }

    return $res;
}



/**
 * Filters an associative array to exclude the data you don't want
 * @param  array    $data   Associative array of data to filter
 * @param  array    $list   List of properties to be excluded
 * @return array            Filtered list of desired properties
 */
function except($data, $list=[]) :array {
    foreach ($list as $i => $key) {
        if ($data[$key]) {
            unset($data[$key]);
        }
    }

    return $data;
}



/**
 * Array helper to determine if a given collection has some required values
 * @param  array    $data Data to be checked
 * @param  array    $list List of required properties to be checked
 * @return boolean        True if passes, false if failed
 */
function has(array $data, array $list=[]) :boolean {
    foreach ($list as $i => $key) {
        if (!$data[$key]) {
            throw new \Exception("Missing property: Data must have property {$key}");
            return false;
        }
    }

    return true;
}



/**
 * Alias for password_hash that provides PASSWORD_ARGON2I by default
 * @param  string $pass Password to be hashed
 * @return string       Hashed password
 */
function hash_password(string $pass) :string {
    return password_hash($pass, PASSWORD_ARGON2I);
}



/**
 * Alias for password_needs_rehash that provides PASSWORD_ARGON2I by default
 * @param  string $hash Password to be hashed
 * @return string       Hashed password
 */
function rehash_password(string $hash) :string {
    return password_needs_rehash($hash, PASSWORD_ARGON2I);
}



/**
 * Iterates over a given directory and requires all files within
 * @param  string $dir [description]
 * @return [type]      [description]
 */
function require_dir(string $dir) {

    $models = scandir($dir);

    foreach ($models as $filepath) {
        if ($filepath === '.' || $filepath === '..' || strpos($filepath, '.php') === false) { continue; }

        $filepath = $dir. '/' . $filepath;

        if (file_exists($filepath)) {
            require_once($filepath);
        }
    }

}