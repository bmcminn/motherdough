<?php

namespace App\Helpers;

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger as Monolog;

/**
 * @sauce: https://gist.github.com/laverboy/fd0a32e9e4e9fbbf9584
 */
class Logger {

    protected static $directory;
    protected static $instance;
    protected static $name;
    protected static array $data;


    public static function setup(array $options) : void {
        self::$data = array_replace_recursive([
            'logs_path' => __DIR__ . '/logs',
            'max_logs' => 5,
        ], $options);
    }


    /**
     * Returns the given config value for a specific setting path
     *
     * @param   string      $key The key
     * @throws  Exception   If $key path does not exist, alert developer instead of defaulting to nullish value
     * @return  any         If $key is null/undefined, returns the whole config array
     * @return  any         The stored value
     */
    public static function get(string $key = null) : array|bool|float|int|string {
        if ($key === null) {
            return self::$data;
        }

        // setup memoization to trivialize future lookups
        static $memocache = [];

        if (isset($memocache[$key])) {
            return $memocache[$key];
        }

        $parts = explode('.', $key);
        $value = self::$data;

        foreach ($parts as $part) {
            if (!isset($value[$part])) {
                throw new \ErrorException("key path does not exist ($key)");
            }

            $value = $value[$part];
        }

        $memocache[$key] = $value;

        return $value;
    }



    public static function setName(string $name) {
        self::$name = $name;
    }

    public static function getName() {
        return self::$name;
    }

    public static function setDirectory(string $directory) {
        self::$directory = $directory;
    }

    public static function getDirectory() {
        return self::$directory;
    }

    /**
     * Method to return the Monolog instance
     *
     * @return \Monolog\Logger
     */
    public static function getLogger() {
        if (! self::$instance) {
            self::configureInstance();
        }

        return self::$instance;
    }

    /**
     * Configure Monolog to use a rotating files system.
     *
     * @return Logger
     */
    protected static function configureInstance() : void {
        $dir = self::getDirectory();

        if (!file_exists($dir)) {
            mkdir($dir, 0644, true);
        }

        $name = self::getName();

        $filepath = $dir . DIRECTORY_SEPARATOR . $name . '.log';

        $logger = new Monolog($name);
        $logger->pushHandler(new RotatingFileHandler($filepath, env('MAX_LOGS', 20)));
        //$logger->pushHandler(new LogglyHandler('eeb5ba83-f0d6-4273-bb1d-523231583855/tag/monolog'));
        self::$instance = $logger;
    }


    protected static function log($level, $msg, $ctx=[]) {
        $msg = implode(' | ', [
            $_SERVER['REMOTE_ADDR'],
            $msg
        ]);
        self::getLogger()->$level($msg, $ctx);
    }


    public static function debug($message, array $context = []) {
        self::log('debug', $message, $context);
    }

    public static function info($message, array $context = []) {
        self::log('info', $message, $context);
    }

    public static function notice($message, array $context = []) {
        self::log('notice', $message, $context);
    }

    public static function warning($message, array $context = []) {
        self::log('warning', $message, $context);
    }

    public static function error($message, array $context = []) {
        self::log('error', $message, $context);
    }

    public static function critical($message, array $context = []) {
        self::log('critical', $message, $context);
    }

    public static function alert($message, array $context = []) {
        self::log('alert', $message, $context);
    }

    public static function emergency($message, array $context = []) {
        self::log('emergency', $message, $context);
    }

}
