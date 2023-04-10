<?php

use App\Helpers\Template;

chdir('_test');

require __DIR__ . '/../vendor/autoload.php';


Template::setup([
    'views_dir' => __DIR__ . '/../src/views',
    'cache_dir' => __DIR__ . '/../storage/cache/views',
    'model' => [
        'app' => [
            'name' => 'sefjsekl',
        ],
    ],
]);


$model = [
    'app' => [
        'name' => 'bob',
    ],
];


echo Template::render('app.twig');
