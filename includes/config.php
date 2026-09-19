<?php

declare(strict_types=1);

function env(string $key, ?string $default = null): ?string
{
    $value = getenv($key);
    return $value !== false ? $value : $default;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo !== null) {
        return $pdo;
    }

    $url = env('MYSQL_URL') ?? env('DATABASE_URL');

    if ($url) {
        $parts = parse_url($url);
        $host = $parts['host'] ?? '127.0.0.1';
        $port = $parts['port'] ?? '3306';
        $user = $parts['user'] ?? 'root';
        $pass = $parts['pass'] ?? '';
        $name = ltrim($parts['path'] ?? '', '/');
    } else {
        $host = env('MYSQLHOST', env('DB_HOST', '127.0.0.1'));
        $port = env('MYSQLPORT', env('DB_PORT', '3306'));
        $user = env('MYSQLUSER', env('DB_USER', 'root'));
        $pass = env('MYSQLPASSWORD', env('DB_PASSWORD', ''));
        $name = env('MYSQLDATABASE', env('DB_NAME', 'railway'));
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
