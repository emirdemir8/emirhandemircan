<?php
declare(strict_types=1);

function env_value(string $key, string $default): string
{
    $value = getenv($key);
    return $value === false || $value === '' ? $default : $value;
}

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = env_value('PORTFOLIO_DB_HOST', 'localhost');
    $port = env_value('PORTFOLIO_DB_PORT', '3307');
    $database = env_value('PORTFOLIO_DB_NAME', 'portfolio_db');
    $username = env_value('PORTFOLIO_DB_USER', 'root');
    $password = env_value('PORTFOLIO_DB_PASS', '');

    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}

function json_response(array $payload, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function clean_string(?string $value): string
{
    return trim((string) $value);
}
