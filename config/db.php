<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$dbname = $_ENV['DB_NAME'] ?? getenv('DB_NAME');
$port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? 3306;
$username = $_ENV['DB_USER'] ?? getenv('DB_USER');
$password = $_ENV['DB_PASS'] ?? getenv('DB_PASS');
$charset = $_ENV['DB_CHARSET'] ?? getenv('DB_CHARSET') ?? 'utf8mb4';

$debug = $_ENV['APP_DEBUG'] ?? getenv('APP_DEBUG') ?? 'false';

if ($debug === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Kết nối DB
$conn = mysqli_connect($host, $username, $password, $dbname, $port);

// Check lỗi
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Charset
mysqli_set_charset($conn, $charset);