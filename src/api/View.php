<?php

namespace App;


use Jenssegers\Blade\Blade;


final class View {


    protected static $blade;


    /**
     * [config description]
     * @param  string      $viewsDir  [description]
     * @param  string|null $cachePath [description]
     * @return [type]                 [description]
     */
    public static function config(string $viewsDir, ?string $cachePath = null) {
        self::$blade = new Blade($viewsDir, $cachePath);
    }


    /**
     * [render description]
     * @param  string $view [description]
     * @param  array  $ctx  [description]
     * @return [type]       [description]
     */
    public static function render(string $view, array $ctx = []) {
        return self::$blade->render($view, $ctx);
    }


    /**
     * [make description]
     * @param  string $view [description]
     * @param  array  $ctx  [description]
     * @return [type]       [description]
     */
    public static function make(string $view, array $ctx = []) {
        return self::$blade->make('homepage', $ctx)->render();
    }


    /**
     * [extend description]
     * @param  string   $name [description]
     * @param  callable $cb   [description]
     * @return [type]         [description]
     */
    public static function extend(string $name, callable $cb) {
        self::$blade->directive($name, $cb);
    }

}
