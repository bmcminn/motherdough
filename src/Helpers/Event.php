<?php

namespace App\Helpers;

use ErrorException;
use RedBeanPHP\Facade as R;


/**
 * This class provides static methods for rendering templates.
 */
class Event {


    public static function log(string $name, $message) {
        $event = R::dispense('event');

        $event->name        = $name;
        $event->messsage    = $message;
        $event->createdAt   = R::isoDateTime();

        R::store($event);
    }

}
