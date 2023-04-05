<?php

namespace App\Helpers;


/**
 * A class wrapper for any given hash library you want to implement; Argon2ID by default
 * @sauce https://medium.com/analytics-vidhya/password-hashing-pbkdf2-scrypt-bcrypt-and-argon2-e25aaf41598e
 * @note https://twitter.com/Sc00bzT/status/1557495201064558592
 */
class Hash {

    protected static string $algo;
    protected static array $options;


    public static function setup(array $options = []) {
        $options = array_replace_recursive([
            'algo' => PASSWORD_ARGON2ID,
            'options' => [
                'memory_cost' => 11264,
                'time_cost' => 3,
                'threads' => 1,
            ]
        ], $options);

        self::$algo = $options['algo'];
        self::$options = $options['options']; // ?? m≥93,750/(3*t-1)*α
    }


    public static function password(string $value) : string {
        return password_hash($value, self::$algo, self::$options);
    }


    public static function verify(string $value, string $hash) : bool {
        return password_verify($value, $hash);
    }

    public static function md5($value) {
        return md5($value);
    }

}
