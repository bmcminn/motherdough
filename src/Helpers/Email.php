<?php

namespace App\Helpers;

use App\Helpers\Config;
use App\Helpers\Template;


class Email {

    private static array $config = [];


    public static function setup(array $options = []) {
        self::$config = array_replace_recursive([

        ], $options);
    }


    public static function sendVerificationEmail(string $email, array $data = []) : bool {
        $success = true;




        return $success;
    }


    public static function sendChangeEmail(string $email, $token) {

    }


    public static function sendForgotPassword(string $email, $token) {
        $content = "Reset token: {$token}";
        $headers = [
            'from' => 'info@site.com',
        ];
        $success = self::composeEmail($email, 'Password reset', $content, $headers);
        return $success;
    }


    private static function composeEmail(string $to, string $subject, string $content, array $headers = []) {

        // TODO: figure out some form of content composition
        $html = $content;

        mail($to, $subject, $content, $headers);
    }


}
