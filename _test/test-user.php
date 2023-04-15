<?php

use App\Helpers\Hash;
use App\Helpers\Token;
use App\Models\Session;
use App\Models\User;


use RedBeanPHP\Facade as R;
use Ramsey\Uuid\Uuid;

chdir('_test');

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$DB_FILE = __DIR__ . '/../storage/database/main.db';

R::setup('sqlite:' . $DB_FILE);
R::useFeatureSet( 'novice/latest' );


$user = User::findByEmail('bobdole@law.blah');


print_r($user);

echo !!$user ? 'TRUE' : 'FALSE';



R::close();
