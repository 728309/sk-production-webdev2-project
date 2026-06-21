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
        $host = getenv('DB_HOST') ?: self::HOST;
        $database = getenv('DB_NAME') ?: self::DATABASE;
        $user = getenv('DB_USER') ?: self::USER;
        $password = getenv('DB_PASSWORD') ?: self::PASSWORD;

        $dsn = 'mysql:host=' . $host . ';dbname=' . $database . ';charset=utf8mb4';

        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
