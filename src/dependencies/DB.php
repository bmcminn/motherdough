<?php

namespace App\Dependencies;

use Slim\Container;
use Tuupola\Middleware\JwtAuthentication;

class DB {

    public function __invoke(Container $c) {

        $settings   = $c->get('settings');
        $logger     = $c->get('logger');

        $DB_DATABASE = env('DB_DATABASE', 'sqlite');
        $DB_PATH     = buildPath(DATA_DIR, env('DB_FILEPATH', ''));

        return \Delight\Db\PdoDatabase::fromDsn(
            new \Delight\Db\PdoDsn(
                "{$DB_DATABASE}:{$DB_PATH}",
                env('DB_USERNAME', null),
                env('DB_PASSWORD', null)
            )
        );
    }

}
