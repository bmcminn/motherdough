<?php

namespace App\Models;

use App\Helpers\Event;
use App\Helpers\Validator;
use App\Models\Session;
use App\Helpers\Hash;


use RedBeanPHP\Facade as R;

// TODO setup database sessions table; store createdAt, expiresAt, id


class User {

    public static array $roles = [
        'admin',
        'moderator',
        'super',
        'user',
    ];


    public static function create($data = []) {
        $user = R::dispense('user');

        $user->dateofbirth  = $data['dateofbirth'];
        $user->email        = $data['email'];
        $user->email_base   = Validator::stripEmailSubaddress($data['email']);
        // $user->firstname    = $data[''];
        // $user->lastname     = $data[''];
        // $user->name         = $data['TEST_USER_FIRST'] . ' ' . $_ENV['TEST_USER_LAST'];
        $user->password     = Hash::password($data['password']);
        $user->roles        = 'user';
        $user->uuid         = uuid4();

        $user->createdAt    = R::isoDateTime(); // now();
        $user->updatedAt    = null;
        $user->deletedAt    = null;

        R::store($user);

        // TODO: Log event
        // TODO: create event entry
        Event::log('user created', $user->uuid);

        return $user;
    }


    public static function findByUuid(string $uuid) {
        return R::findOne('user', 'uuid = ?', [ $uuid ]);
    }


    public static function findByEmail(string $email) {
        $email = trim($email);
        return R::findOne('user', 'email = ?', [ $email ]);
    }


    public static function update($data = []) {

    }

    public static function delete($userId) {

    }


    public static function addRole($role) {

    }


    public static function removeRole($role) {

    }

}
