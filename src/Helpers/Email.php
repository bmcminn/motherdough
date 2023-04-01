<?php

namespace App\Helpers;

use App\Helpers\Config;


class Email {


    public static function setup(array $options = []) {

    }


    public static function sendVerificationEmail(string $email, array $data = []) {

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
