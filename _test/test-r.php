<?php

use App\Helpers\Hash;

use RedBeanPHP\Facade as R;
use Ramsey\Uuid\Uuid;


require __DIR__ . '/../vendor/autoload.php';

$DB_FILE = __DIR__ . '/../storage/database/main.db';

print_r($DB_FILE);

R::setup('sqlite:' . $DB_FILE);

Hash::setup();


$validRoles = [
    'admin', 'user', 'super', 'moderator',
];


// GENERATE USER
$user = R::dispense('user');

$user->uuid         = uuid4();
$user->createdAt    = now();

R::store($user);


// GENERATE USER SESSION
$session = R::dispense('user_session');

$session->uuid      = uuid4();
$session->userUuid  = $user->uuid;

$session->createdAt = now();
$session->expiresAt = now() + hours(24);

R::store($session);


//




function newAdmin($options) {
    $password = 'Testing123';

    $user = R::dispense('user');
    $user->dateofbirth  = '1990-01-01';
    $user->email        = 'bob@law.blah';
    $user->email_base   = 'bob@law.blah';
    $user->firstname    = 'Bob';
    $user->lastname     = 'Lawblah';
    $user->name         = 'Bob Lawblah';
    $user->password     = Hash::password($password);
    $user->roles        = implode('|', $validRoles);

    $user->createdAt    = now();
    $user->updatedAt    = null;
    $user->deletedAt    = null;


    $id = R::store($user);

    print_r($id);
    print_r($user);
}


R::close();
