<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env nếu có (local)
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

// Lấy DATABASE_URL
$databaseUrl = $_ENV['DATABASE_URL'] ?? getenv('DATABASE_URL');

if (!$databaseUrl) {
    die("DATABASE_URL not set");
}

// Parse URL
$parsed = parse_url($databaseUrl);

$host = $parsed['host'] ?? null;
$port = $parsed['port'] ?? 3306;
$username = $parsed['user'] ?? null;
$password = $parsed['pass'] ?? null;
$dbname = ltrim($parsed['path'], '/');
$charset = 'utf8mb4';

// Debug mode
$debug = $_ENV['APP_DEBUG'] ?? getenv('APP_DEBUG') ?? 'false';

if ($debug === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Connect
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, $charset);