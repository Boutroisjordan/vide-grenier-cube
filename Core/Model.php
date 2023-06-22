<?php

namespace Core;

use PDO;

/**
 * Base model
 *
 * PHP version 7.0
 */
abstract class Model
{

    /**
     * Get the PDO database connection
     *
     * @return PDO|null
     */
    protected static function getDB(): ?PDO
    {
        static $db = null;


        if ($db === null) {
            $dsn = 'mysql:host=mySql_' . $_ENV['APP_ENV'] . ';dbname=' . $_ENV['MYSQL_DATABASE'] . ';charset=utf8;port=' . $_ENV['MYSQL_PORT'];
            $db = new PDO($dsn, $_ENV['MYSQL_USER'], $_ENV['MYSQL_PASSWORD']);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
}
