<?php

use App\Logger;
use App\View;
use Pecee\SimpleRouter\SimpleRouter as Router;



Router::get('/', function() {

    return View::render('api.homepage', [ 'isDev' => is_dev() ]);

});


if (is_dev()) {
	Router::get('/test/views/{type}/{status}', function(string $type, string $status) {

        $template = "{$type}.{$status}";

        Logger::debug('testing', $template);
		// TODO: possible MJML integration point: https://packagist.org/packages/rennokki/laravel-mjml
        // TODO: possible to develop MJML extensions for Blade

	    return View::render($template);

	});
}



// Define
Router::post('/auth/user',   '\App\Controllers\UserController@register');
Router::post('/auth/login',  '\App\Controllers\UserController@login');
