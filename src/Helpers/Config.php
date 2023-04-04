<?php

namespace App\Helpers;


class Config {

    private static array $data = [

    ];


    /**
     * { function_description }
     *
     * @param      array  $data   The data
     */
    public static function setup(array $data) : void {
        self::$data = $data;
    }


    /**
     * Returns the given config value for a specific setting path
     *
     * @param   string      $key The key
     * @throws  Exception   If $key path does not exist, alert developer instead of defaulting to nullish value
     * @return  any         If $key is null/undefined, returns the whole config array
     * @return  any         The stored value
     */
    public static function get(string $key = null) : array|bool|float|int|string {
        if ($key === null) {
            return self::$data;
        }

        // setup memoization to trivialize future lookups
        static $memocache = [];

        if (isset($memocache[$key])) {
            return $memocache[$key];
        }

        $parts = explode('.', $key);
        $value = self::$data;

        foreach ($parts as $part) {
            if (!isset($value[$part])) {
                throw new \ErrorException("key path does not exist ($key)");
            }

            $value = $value[$part];
        }

        $memocache[$key] = $value;

        return $value;
    }


    public static function set(array $value) {
        self::$data = array_merge_recursive(self::$data, $value);
    }

}
