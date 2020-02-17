<?php

namespace App\Helpers;


class Mailer {


    public static function sendChangeEmail($email, $token) {

    }


    public static function sendForgotPassword($email, $token) {

    }


    private static function composeEmail(string $to, string $subject, string $content, array $headers = []) {

        // TODO: figure out some form of content composition
        $html = $content;

        mail($to, $subject, $content, $headers)
    }


}
