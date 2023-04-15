<?php

namespace App\Helpers;

use Liquid\Liquid;
use Liquid\Template as LiquidTemplate;
use Liquid\Cache\Local;


use Twig;

use ErrorException;


/**
 * This class provides static methods for rendering templates.
 */
class Template {

    protected static array $data = [];
    protected static $template;

    /**
     * Run Template thorugh its initial configuration.
     *
     * @param   array   $options    The options
     */
    public static function setup(array $options) : void {
        self::$data = array_replace_recursive([
            'views_dir' => __DIR__ . '/views',
            'cache_dir' => null,
            'model'     => [],
            'filters'   => [],
            'twig'      => [],
            // 'liquid' => [
            //     'escape_by_default' => true,
            //     'include_allow_ext' => true,
            //     'include_prefix'    => '_',
            //     'include_suffix'    => 'liquid',
            //     'filters'           => [],
            // ],
        ], $options);


        $loader = new \Twig\Loader\FilesystemLoader(self::config('views_dir'));

        self::$template = new \Twig\Environment($loader, self::config('twig'));

        foreach (self::config('filters') as $key => $cb) {
            self::$template->addFilter(new \Twig\TwigFilter($key, $cb));
        }
    }



    /**
     * Returns the given config value for a specific setting path
     *
     * @param   string      $key The key
     * @throws  Exception   If $key path does not exist, alert developer instead of defaulting to nullish value
     * @return  any         If $key is null/undefined, returns the whole config array
     * @return  any         The stored value
     */
    public static function config(string $key = null) {
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
                throw new ErrorException("key path does not exist ($key)");
            }

            $value = $value[$part];
        }

        $memocache[$key] = $value;

        return $value;
    }



    /**
     * Render a given file located in the configured views_dir
     *
     * @param      string  $name   The name
     * @param      array   $model  The model
     *
     * @return     string  the rendered template string
     */
    public static function render(string $name, array $model=[]) : string {
        // $filepath = self::config('views_dir') . '/' . trim($name, '/');
        // $src = file_get_contents($filepath);
        $model = array_replace_recursive(self::config('model'), $model);

        $filename = $name . self::config('ext');

        return self::$template->render($filename, $model);
    }



    // /**
    //  * Render a given template string
    //  *
    //  * @param      string  $src    The source
    //  * @param      array   $model  The model
    //  *
    //  * @return     string  the rendered template string
    //  */
    // public static function renderString(string $src, array $model=[]) : string {

    //     $model = array_replace_recursive(self::config('model'), $model);

    //     return self::$template->parse($src)->render((array) $model);
    // }

}

// /**
//  * This class provides static methods for rendering templates.
//  */
// class Template {

//     protected static array $data = [];
//     protected static LiquidTemplate $template;

//     /**
//      * Run Template thorugh its initial configuration.
//      *
//      * @param   array   $options    The options
//      */
//     public static function setup(array $options) : void {
//         self::$data = array_replace_recursive([
//             'views_dir'     => __DIR__ . '/views',
//             'cache_dir'     => null,
//             'model'         => [],
//             'liquid' => [
//                 'escape_by_default' => true,
//                 'include_allow_ext' => true,
//                 'include_prefix'    => '_',
//                 'include_suffix'    => 'liquid',
//                 'filters'           => [],
//             ],
//         ], $options);

//         Liquid::set('ESCAPE_BY_DEFAULT',    self::config('liquid.escape_by_default'));
//         Liquid::set('INCLUDE_ALLOW_EXT',    self::config('liquid.include_allow_ext'));
//         Liquid::set('INCLUDE_PREFIX',       self::config('liquid.include_prefix'));
//         Liquid::set('INCLUDE_SUFFIX',       self::config('liquid.include_suffix'));

//         self::$template = new LiquidTemplate(
//             self::config('views_dir'),
//             self::config('cache_dir')
//         );

//         foreach (self::config('liquid.filters') as $key => $cb) {
//             self::$template->registerFilter($key, $cb);
//         }
//     }



//     /**
//      * Returns the given config value for a specific setting path
//      *
//      * @param   string      $key The key
//      * @throws  Exception   If $key path does not exist, alert developer instead of defaulting to nullish value
//      * @return  any         If $key is null/undefined, returns the whole config array
//      * @return  any         The stored value
//      */
//     public static function config(string $key = null) {
//         if ($key === null) {
//             return self::$data;
//         }

//         // // setup memoization to trivialize future lookups
//         // static $memocache = [];

//         // if (isset($memocache[$key])) {
//         //     return $memocache[$key];
//         // }

//         $parts = explode('.', $key);
//         $value = self::$data;

//         foreach ($parts as $part) {
//             if (!isset($value[$part])) {
//                 throw new ErrorException("key path does not exist ($key)");
//             }

//             $value = $value[$part];
//         }

//         // $memocache[$key] = $value;

//         return $value;
//     }



//     /**
//      * Render a given file located in the configured views_dir
//      *
//      * @param      string  $name   The name
//      * @param      array   $model  The model
//      *
//      * @return     string  the rendered template string
//      */
//     public static function render(string $name, array $model=[]) : string {
//         $filepath = self::config('views_dir') . '/' . trim($name, '/');
//         $src = file_get_contents($filepath);
//         return self::renderString($src, $model);
//     }



//     /**
//      * Render a given template string
//      *
//      * @param      string  $src    The source
//      * @param      array   $model  The model
//      *
//      * @return     string  the rendered template string
//      */
//     public static function renderString(string $src, array $model=[]) : string {

//         $model = array_replace_recursive(self::config('model'), $model);

//         return self::$template->parse($src)->render((array) $model);
//     }

// }
