<?php

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



function logger(string $logType='INFO', array $args = []) {

    $logDate    = date('Y-m-d');
    $logPath    = ROOT_DIR . '/logs/' . "{$logDate}.log";
    $timestamp  = date('Y-m-d H:i:s');

    $msg = [
        "[$timestamp]",
        ':',
        "[$logType]",
    ];

    foreach ($args as $key => $value) {
        $part = '';
        $type = gettype($value);

        switch($type) {
            case 'object':
            case 'array':
                $part = 'JSON ' . json_encode($value);
                break;
            default:
                $part = (string) $value;
                break;
        }

        array_push($msg, $part);
    }

    array_push($msg, PHP_EOL);

    $msg = implode(' ', $msg);

    return $msg;
}



function log_error() {
    $args = func_get_args();
    echo logger('ERROR', $args);
}



function log_debug() {
    $args = func_get_args();
    echo logger('DEBUG', $args);
}
