<?php

use App\Helpers\Hash;

use RedBeanPHP\Facade as R;
use Ramsey\Uuid\Uuid;

chdir('_test');

require __DIR__ . '/../vendor/autoload.php';


$validRoles = [
    'admin', 'user', 'super', 'moderator',
];


function test_createUserSession() {
    // FIND USER
    $email = $_ENV['TEST_USER_EMAIL'];
    $user = R::findOne('user', ' email = ?', [ $email ]);

    // GENERATE USER SESSION
    $session = R::dispense('usersession');

    $session->uuid      = uuid4();
    $session->userUuid  = $user->uuid;

    // TODO: change now() to a DateTime() conversion _OR_ R::isoDate()
    $session->createdAt = now();
    $session->expiresAt = now() + hours(24);

    R::store($session);
}


test_createUserSession();

//


function newAdmin($options) {
    $user = R::dispense('user');
    $user->dateofbirth  = $_ENV['TEST_USER_DOB'];
    $user->email        = $_ENV['TEST_USER_EMAIL'];
    $user->email_base   = $_ENV['TEST_USER_EMAIL'];
    $user->firstname    = $_ENV['TEST_USER_FIRST'];
    $user->lastname     = $_ENV['TEST_USER_LAST'];
    $user->name         = $_ENV['TEST_USER_FIRST'] . ' ' . $_ENV['TEST_USER_LAST'];
    $user->password     = Hash::password($_ENV['TEST_USER_PASS']);
    $user->roles        = implode('|', $validRoles);

    $user->createdAt    = now();
    $user->updatedAt    = null;
    $user->deletedAt    = null;

    $id = R::store($user);

    print_r($id);
    print_r($user);
}


R::close();
