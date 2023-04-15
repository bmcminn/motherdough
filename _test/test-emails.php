<?php

use App\Helpers\Config;
use App\Helpers\Email;
use App\Helpers\Token;

use App\Models\User;

chdir('_test');

require __DIR__ . '/../vendor/autoload.php';


$model = Config::get();

$model['user']  = User::findByEmail('bob@law.blah');
$model['otp']   = '123456';

$status = Email::sendLoginOTP($model);

print_r($status);
