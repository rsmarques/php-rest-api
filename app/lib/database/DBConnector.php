<?php

namespace app\lib\database;

class DBConnector
{
    /**
    * Returns the default database from config file
    *
    * @return
    */
    public static function getDefaultDatabase()
    {
        $database       = null;

        $DBConfig       = include(__DIR__ . '/../../config/database.php');
        $defaultDB      = $DBConfig['default'];

        // database instantiating is done here
        switch ($defaultDB) {
            case 'firebase':
                $database   = new FirebaseDB;
                break;

            default:
                break;
        }

        return $database;
    }
}
