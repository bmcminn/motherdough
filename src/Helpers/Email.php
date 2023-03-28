<?php

namespace App\Helpers;


class Email {

    protected static array $data;


    /**
     * { function_description }
     *
     * @param      array  $options  The options
     */
    public static function setup(array $options) : void {
        self::$data = array_replace_recursive([

        ], $options);
    }



    public function render($template, $model) {

    }

}
