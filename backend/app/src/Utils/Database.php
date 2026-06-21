<?php

namespace App\Utils;

use PDO;

class Database
{
    private const HOST = 'mysql';
    private const DATABASE = 'developmentdb';
    private const USER = 'developer';
    private const PASSWORD = 'secret123';

    public static function connect(): PDO
    {
        $dsn = 'mysql:host=' . self::HOST . ';dbname=' . self::DATABASE . ';charset=utf8mb4';

        return new PDO($dsn, self::USER, self::PASSWORD, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
