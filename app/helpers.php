<?php


//  TIME HELPER FUNCITON(S)
// ===================================

function minutes(int $n=1)  :int { return $n * 60; }
function hours(int $n=1)    :int { return $n * 60 * $minutes; }
function days(int $n=1)     :int { return $n * 24 * $hours; }
function weeks(int $n=1)    :int { return $n * 7 * $days; }
function months(int $n=1)   :int { return $n * 30 * $days; }
function years(int $n=1)    :int { return $n * 365 * $days; }

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

/**
 * [logger description]
 * @param  string $logType [description]
 * @param  array  $args    [description]
 * @return [type]          [description]
 */
function logger(string $logType='INFO', int $weight, array $args = []) {

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


function log_debug()     { echo logger('DEBUG',     100,  func_get_args()); }
function log_info()      { echo logger('INFO',      200,  func_get_args()); }
function log_notice()    { echo logger('NOTICE',    250,  func_get_args()); }
function log_warning()   { echo logger('WARNING',   300,  func_get_args()); }
function log_error()     { echo logger('ERROR',     400,  func_get_args()); }
function log_critical()  { echo logger('CRITICAL',  500,  func_get_args()); }
function log_alert()     { echo logger('ALERT',     550,  func_get_args()); }
function log_emergency() { echo logger('EMERGENCY', 600,  func_get_args()); }
