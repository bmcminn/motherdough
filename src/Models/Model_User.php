<?php

// namespace App\Models;

use App\Logger;
use RedBeanPHP\SimpleModel;


class Model_User extends SimpleModel {

    public function update() {

        $test = R::find('user', 'username LIKE ? OR email LIKE ?', [
            $this->bean->username,
            $this->bean->email,
        ]);

        if (!empty($test)) {
            $msg = 'Username and/or email is already associated with an account.';
            Logger::error($msg);
            throw new \Exception($msg);
            // throw new \UserExistsException($msg);
        }

    }
}