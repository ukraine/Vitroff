<?php
/* ORM config */

function init_orm() {
    ORM::configure(
        array(
            'connection_string'                => "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
            'username'                         => DB_USER,
            'password'                         => DB_PASS,
            'logging'                          => false,

            PDO::MYSQL_ATTR_INIT_COMMAND       => 'SET NAMES cp1251; SET wait_timeout=28800;',
            PDO::ATTR_ERRMODE                  => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT               => true,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        )
    );
}
