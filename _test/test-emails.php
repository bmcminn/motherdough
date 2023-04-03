<?php

use App\Helpers\Token;
use App\Helpers\Email;

require __DIR__ . '/../vendor/autoload.php';


$model = Config::get();

$model['user'] = User::findByEmail('bob@law.blah');


$status = Email::sendVerificationEmail($model);

print_r($status);
