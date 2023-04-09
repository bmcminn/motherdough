<?php

use App\Helpers\Token;
use App\Helpers\Email;

require __DIR__ . '/../vendor/autoload.php';


$model = Config::get();

$model['user'] = User::findByEmail('bob@law.blah');
$model['otp'] = '123456';

$status = Email::sendLoginOTP($model);

print_r($status);
