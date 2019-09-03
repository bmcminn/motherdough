<?php


function env($key, $default=null) {

    $value = getenv($key);

    if (!$value && $default) {
        return $default;
    }

    if ($value === 'true') {
        return true;
    }

    if ($value === 'false') {
        return false;
    }

    return $value;
}

