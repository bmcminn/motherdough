<?php

// namespace App\Models;

use App\Logger;
use RedBeanPHP\SimpleModel;


class UserExistsException extends Exception {
    // // Redefine the exception so message isn't optional
    // public function __construct($message, $code = 0, Exception $previous = null) {
    //     // some code

    //     // make sure everything is assigned properly
    //     parent::__construct($message, $code, $previous);
    // }

    // // custom string representation of object
    // public function __toString() {
    //     return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    // }

    // public function customFunction() {
    //     echo "A custom function for this type of exception\n";
    // }
}



class Model_User extends SimpleModel {

    public function update() {

        $test = R::find('user', 'username LIKE ? OR email LIKE ?', [
            $this->bean->username,
            $this->bean->email,
        ]);

        if (!empty($test)) {
            $msg = 'Username and/or email is already associated with an account.';
            Logger::error($msg);
            throw new \UserExistsException($msg);
            // throw new \UserExistsException($msg);
        }

    }
}