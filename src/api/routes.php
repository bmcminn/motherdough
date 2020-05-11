<?php

use App\Logger;
use App\View;
use Pecee\SimpleRouter\SimpleRouter as Router;



Router::get('/', function() {

    return View::render('api.homepage');

});


if (is_dev()) {
	Router::get('/test/views/{type}/{status}', function(string $type, string $status) {

		// TODO: possible MJML integration point: https://packagist.org/packages/rennokki/laravel-mjml

	    return View::render("{$type}.{$status}");

	});
}



// Define
Router::post('/auth/user',   '\App\Controllers\UserController@register');
Router::post('/auth/login',  '\App\Controllers\UserController@login');
