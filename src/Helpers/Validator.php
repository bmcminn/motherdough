<?php

namespace App\Helpers;

use ErrorException;


/**
 * This class provides static methods for rendering templates.
 */
class Validator {


    /**
     * Accepts data and a ruleset for validating and sanitizing a given data model
     *
     * @param      array        $data   The data
     * @param      array        $rules  The rules
     * @example [
     *              [ 'key', 'rakit|validation(s)', sanitizer<string|CONSTANT> ],
     *              ...
     *          ]
     *
     * @throws     \ErrorException  Throws error if sanitization string label does not exist
     *
     * @return     array        ( description_of_the_return_value )
     */
    public static function validate(array &$data, array &$rules) : array {
        $validator = new \Rakit\Validation\Validator();

        $validators = [];
        $sanitizers = [];

        foreach ($rules as $el) {
            [ $key, $rule, $sanitizer ] = $el;

            $validators[$key] = $rule;
            $sanitizers[$key] = $sanitizer;
        }


        // VALIDATE THE INPUTS
        $validation = $validator->make($data, $validators);

        if ($validation->fails()) {
            // handling errors
            return $validation->errors();
        }


        // SANITIZE THE INPUTS
        foreach ($sanitizers as $key => $sanitizer) {

            if (is_string($sanitizer)) {
                $sanitizer = trim(strtolower($sanitizer));

                switch($sanitizer) {
                    case 'email':
                        self::sanitizeEmail($data, $key);
                        break;
                    default:
                        throw new \ErrorException("Missing \$sanitizer method for '$sanitizer'.");
                        break;
                }

                continue;
            }

            $data[$key] = filter_var($data[$key], $sanitizer);
        }

        return $data;
    }



    // ----------------------------------------
    //  PRIVATE SANITIZATION METHODS
    // ----------------------------------------


    /**
     * Sanitizes email address and returns the provided email string and the base email string for future comparisons
     *
     * @link https://www.lifewire.com/elements-of-email-address-1166413
     * @param      array    $data   The data
     * @param      string   $key    The key
     */
    private static function sanitizeEmail(array &$data, string $key) {
        $email = filter_var($data[$key], FILTER_SANITIZE_EMAIL);

        $data[$key] = $email;

        // ASCII filtering based on https://stackoverflow.com/a/22786548/3708807
        $email = iconv('UTF-8', 'ASCII//TRANSLIT', $email);

        // remove +subaddress and return raw base email string
        $email = preg_replace('/\+[\s\S]+?\@/', '@', $email);

        $data[$key . '_base'] = $email;
    }



    // ----------------------------------------
    //  PUBLIC VALIDATION MODELS
    // ----------------------------------------


    /**
     * Run Template thorugh its initial configuration.
     *
     * @param   array   $options    The options
     */
    public static function loginModel(array &$data) : array {

        $rules = [
            [ 'email',      'required',         'email' ],
            [ 'password',   'required|min:8',   FILTER_SANITIZE_STRING ],
        ];

        return self::validate($data, $rules);
    }


}
