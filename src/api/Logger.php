<?php

namespace App;


use Monolog\Logger as Monologger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;


final class Logger {

    protected static $logger;


    public const DEBUG     = Monologger::DEBUG;
    public const INFO      = Monologger::INFO;
    public const NOTICE    = Monologger::NOTICE;
    public const WARNING   = Monologger::WARNING;
    public const ERROR     = Monologger::ERROR;
    public const CRITICAL  = Monologger::CRITICAL;
    public const ALERT     = Monologger::ALERT;
    public const EMERGENCY = Monologger::EMERGENCY;


    public static function config(array $config): void {

        $config = array_replace([
            'name'          => 'logs',
            'path'          => 'path/to/logs',
            'level'         => Monologger::WARNING,
            'maxFiles'      => 0,
            'debug'         => false,

            // formatter definitions
            'dateFormat'    => '[Y-m-d | H:i:s.v | U]',
            'logFormat'     => "%datetime% %channel%.%level_name%: %message% %context% %extra%\n",
            'allowInlineLineBreaks' => false,
            'ignoreEmptyContextAndExtra' => true,
        ], $config);


        extract($config);


        // define custom formatter
        $formatter  = new LineFormatter(
            $logFormat,
            $dateFormat,
            $allowInlineLineBreaks,
            $ignoreEmptyContextAndExtra,
        );


        // init logger instance
        self::$logger = new Monologger($name);


        // allow for rotating logs or a single log file target
        $stream;

        $logPath = "{$path}/{$name}.log";

        if (0 < $maxFiles) {
            $stream = new RotatingFileHandler($logPath, $maxFiles, $level);
            $stream->setFilenameFormat('{date}.{filename}', 'Y-m-d');

        } else {
            $stream = new StreamHandler($logPath, $level);

        }

        // the default date format is "Y-m-d\TH:i:sP"
        $stream->setFormatter($formatter);

        self::$logger->pushHandler($stream);


        // allow for writing logs to console
        if ($debug) {
            $stream = new StreamHandler('php://stdout');
            $stream->setFormatter($formatter);

            self::$logger->pushHandler($stream);
        }

    }


    /**
     * [_log description]
     * @param  array    $args   The message parts to be stringified to be logged
     * @return string           The stringified message to be logged
     */
    private function _log(array $args): string {

        foreach ($args as $i => $value) {

            $argType = trim(strtolower(gettype($value)));

            switch($argType) {
                case 'array':
                case 'object':
                    $args[$i] = json_encode($value);
                    break;

                case 'boolean':
                    $args[$i] = $value ? 'true' : 'false';
                    break;
            }

            $args[$i] = trim($value);
        }


        return implode(' ', $args);

    }


    /**
     * [debug description]
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    public static function debug(...$args): void {
        self::$logger->debug(self::_log($args));
    }


    /**
     * [info description]
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    public static function info(...$args): void {
        self::$logger->info(self::_log($args));
    }


    /**
     * [notice description]
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    public static function notice(...$args): void {
        self::$logger->notice(self::_log($args));
    }


    /**
     * [warning description]
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    public static function warning(...$args): void {
        self::$logger->warning(self::_log($args));
    }


    /**
     * [error description]
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    public static function error(...$args): void {
        self::$logger->error(self::_log($args));
    }


    /**
     * [critical description]
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    public static function critical(...$args): void {
        self::$logger->critical(self::_log($args));
    }


    /**
     * [alert description]
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    public static function alert(...$args): void {
        self::$logger->alert(self::_log($args));
    }


    /**
     * [emergency description]
     * @param  [type] $args [description]
     * @return [type]       [description]
     */
    public static function emergency(...$args): void {
        self::$logger->emergency(self::_log($args));
    }


}
