<?php

use RedBeanPHP\Facade as R;
use App\Helpers\Hash;

require __DIR__ . '/../vendor/autoload.php';

Hash::setup();

chdir('_test');

$password = 'testpassword';

$hash = Hash::password($password);


echo $password . PHP_EOL;
echo $hash . PHP_EOL;
echo (Hash::verify($password, $hash) ? 'TRUE' : 'FALSE') . PHP_EOL;


