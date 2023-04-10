<?php

use App\Helpers\Token;

chdir('_test');

require __DIR__ . '/../vendor/autoload.php';

Token::setup();


$hashes = [
    'sha256'    => Token::generateSHA256(),
    'sha512'    => Token::generateSHA512(),
    'md5'       => Token::generateMD5(),
];


print_r($hashes);

