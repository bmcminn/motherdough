<?php

namespace App;


class Logger {

    private static $timestampFormat = 'Y-m-d|H:i:s';
    private static $logFilename     = '';
    private static $logLevel        = 100;
    private static $logTarget       = 'php://stdout';
    private static $numLogs         = 0;
    private static $logTargets      = [];


    const PHP_OUT     = 'php://stdout';
    const DEBUG       = 100;
    const INFO        = 200;
    const NOTICE      = 250;
    const WARNING     = 300;
    const ERROR       = 400;
    const EVENT       = 450;
    const CRITICAL    = 500;
    const ALERT       = 550;
    const EMERGENCY   = 600;


    private static $logLabels = [
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        450 => 'EVENT',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    ];


    public static function config(array $config) {
        foreach ($config as $key => $value) {
            if ($key === 'logName')         { self::$logName($value); }
            if ($key === 'timestampFormat') { self::timestampFormat($value); }
            if ($key === 'logLevel')        { self::setLogLevel($value); }
            if ($key === 'logTarget')       { self::setLogTarget($value); }
            if ($key === 'numLogs')         { self::setNumLogs($value); }

            // if ($key === 'logTargets')      { self::$logTargets = $value; }
        }
    }


    public static function setFilename(string $logFilename) {
        self::$logFilename = trim($logFilename);
    }

    public static function setLogTarget(string $target) {
        self::$logTarget = trim($target);
    }


    public static function setNumLogs(int $num) {
        self::$numLogs = $num;

        // if ($num > 0) {
        //     self::_cleanupLogsFolder();
        // }
    }


    public static function timestampFormat(string $format) {
        self::$timestampFormat = trim($format);
    }


    public static function setLogLevel(string $level) {
        $level = strtoupper($level);

        // You cannot set log level higher than critical
        if ($logLabels[$level] > self::$CRITICAL) {
            self::$logLevel = self::$CRITICAL;
        }

        foreach (self::$logLabels as $key => $label) {
            if ($level === $label) {
                self::$logLevel = $key;
                break;
            }
        }
    }



    private static function _cleanupLogsFolder() {

        $logs = scandir(dirname(self::$logTarget), SCANDIR_SORT_DESCENDING);
        $list = array_slice($logs, self::$numLogs);

        foreach ($list as $file) {
            if ($file === '.' || $file === '..' ) {
                continue;
            }

            unlink($file);
        }
    }



    private static function _callLogger(int $level, ...$args) {
        if (count(self::$logTargets) > 0) {
            foreach (self::$logTargets as $target) {

                if (trim($target) === 'cli') {
                    self::setLogTarget(self::PHP_OUT);

                } else {
                    self::setLogTarget($target);

                }

                self::_logger($level, $args);
            }
        }
    }



    private static function _logger(int $level, ...$args) {

        // if level is less than logLevel, ignore logging the message
        if ($level < self::$logLevel) { return; }

        $logType = self::$logLabels[$level];

        foreach ($args as $i => $value) {
            if (gettype($value) === 'array') {
                $args[$i] = json_encode($value);
            }

            if (gettype($value) === 'object') {
                $args[$i] = json_encode($value);
            }

            if (gettype($value) === 'boolean') {
                $args[$i] = $value ? 'true' : 'false';
            }
        }

        $timestamp = date(self::$timestampFormat);

        $msg = implode(' ', $args);

        $log = "[{$timestamp}] {$logType}: $msg" . PHP_EOL;

        file_put_contents(self::$logTarget, $log, FILE_APPEND);
    }

    public static function debug(...$args)      { return self::_logger(self::DEBUG, ...$args); }
    public static function info(...$args)       { return self::_logger(self::INFO, ...$args); }
    public static function notice(...$args)     { return self::_logger(self::NOTICE, ...$args); }
    public static function warning(...$args)    { return self::_logger(self::WARNING, ...$args); }
    public static function error(...$args)      { return self::_logger(self::ERROR, ...$args); }
    public static function event(...$args)      { return self::_logger(self::EVENT, ...$args); }
    public static function critical(...$args)   { return self::_logger(self::CRITICAL, ...$args); }
    public static function alert(...$args)      { return self::_logger(self::ALERT, ...$args); }
    public static function emergency(...$args)  { return self::_logger(self::EMERGENCY, ...$args); }


}
