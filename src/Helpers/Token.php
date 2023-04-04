<?php

namespace App\Helpers;

use ErrorException;


/**
 * A simple token class for generating random hash tokens.
 *
 * This utility is not gauranateed to be cryptographically secure and is only
 *  intended to be used for generating token identifiers
 */
class Token {

    private static array $algos = [];


    /**
     * { function_description }
     */
    public static function setup() : void {
        self::$algos = hash_hmac_algos();
    }


    /**
     * { function_description }
     *
     * @param       string      $type       The type
     * @param       <array>     $options    The options
     * @property        any         $data       Data to be hashed
     * @property        any         $secret     Shared secret key used to genreate the HMAC variant
     * @property        bool        $binary     True to output raw binary data; default false for lowercase hexits
     *
     * @throws     \ErrorException  (description)
     *
     * @return     string                   The resulting HMAC hash string
     */
    public static function generate(string $type, array $options = []) : string {

        static $algos = $algos ?? hash_hmac_algos();

        if (empty(self::$algos)) {
            throw new \ErrorException('Token::setup() must be run before calling any generate() method.');
        }

        $options = array_replace_recursive([
            'data'      => time() . rand(1000, 10000),
            'secret'    => 'itsasecrettoeveryone',
            'binary'    => false,
        ]);

        $type       = trim(strtolower($type));
        $data       = $options['data'];
        $hashkey    = $options['secret'];
        $binary     = (bool) $options['binary'];

        if (!in_array($type, self::$algos)) {
            throw new \ErrorException("HMAC Algorithm {$type} does not exist. Use a valid hash type defined in https://www.php.net/manual/en/function.hash-hmac-algos.php");
            exit;
        }

        return hash_hmac($type, $data, $hashkey, $binary);
    }



    public static function generateMD5(array $options = []) {
        return self::generate('md5', $options);
    }


    public static function generateSHA256(array $options = []) {
        return self::generate('sha256', $options);
    }


    public static function generateSHA512(array $options = []) {
        return self::generate('sha512', $options);
    }


    public static function generateWhirlpool(array $options = []) {
        return self::generate('whirlpool', $options);
    }


    public static function generateGost(array $options = []) {
        return self::generate('gost', $options);
    }


    public static function generateHaval(array $options = []) {
        return self::generate('haval256,3', $options);
    }

}
