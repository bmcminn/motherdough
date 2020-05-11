<?php

namespace App;


use Jenssegers\Blade\Blade;


final class View {


    protected static $blade;


    public static function config(string $viewsDir, ?string $cachePath = null) {
        self::$blade = new Blade($viewsDir, $cachePath);
    }


    public static function render(string $view, array $ctx = []) {
        return self::$blade->render($view, $ctx);
    }


    public static function make(string $view, array $ctx = []) {
        return self::$blade->make('homepage', $ctx)->render();
    }


    public static function directive(string $name, callable $cb) {
        self::$blade->directive($name, $cb);
    }

}
