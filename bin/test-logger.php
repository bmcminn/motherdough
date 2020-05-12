<?php

use App\Logger;
use Monolog\Logger as Monologger;



require __DIR__ . '/../vendor/autoload.php';


Logger::config([
    'name' => 'test',
    'path' => storage_path('logs/test.log'),
    'debug' => true,
    'level'     => Monologger::DEBUG,
]);


Logger::debug('this is a DEBUG entry');
Logger::info('this is a INFO entry');
Logger::notice('this is a NOTICE entry');
Logger::warning('this is a WARNING entry');
Logger::error('this is a ERROR entry');
Logger::critical('this is a CRITICAL entry');
Logger::alert('this is a ALERT entry');
Logger::emergency('this is a EMERGENCY entry');