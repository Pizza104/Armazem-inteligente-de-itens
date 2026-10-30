<?php
declare(strict_types=1);

final class Conn
{
    public function conectar(): PDO
    {
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $database = getenv('DB_NAME') ?: 'empresa';
        $user = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';
        $charset = getenv('DB_CHARSET') ?: 'utf8mb4';
        $port = filter_var(getenv('DB_PORT') ?: '3306', FILTER_VALIDATE_INT);

        if ($port === false || $port < 1 || $port > 65535) {
            throw new RuntimeException('DB_PORT precisa ser um número entre 1 e 65535.');
        }

        $dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $database, $charset);
        return new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
