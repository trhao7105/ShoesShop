<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

$host     = $_ENV['DB_HOST']     ?? getenv('DB_HOST');
$dbname   = $_ENV['DB_NAME']     ?? getenv('DB_NAME');
$port     = $_ENV['DB_PORT']     ?? getenv('DB_PORT') ?? 4048;
$username = $_ENV['DB_USER']     ?? getenv('DB_USER');
$password = $_ENV['DB_PASS']     ?? getenv('DB_PASS');
$charset  = $_ENV['DB_CHARSET']  ?? getenv('DB_CHARSET') ?? 'utf8mb4';

$debug = $_ENV['APP_DEBUG'] ?? getenv('APP_DEBUG') ?? 'false';

if ($debug === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

$conn = mysqli_init();

if (!$conn) {
    die("mysqli_init() failed");
}

mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);   

$success = mysqli_real_connect(
    $conn,
    $host,
    $username,
    $password,
    $dbname,
    (int)$port,
    NULL,
    MYSQLI_CLIENT_SSL | MYSQLI_CLIENT_SSL_DONT_VERIFY_SERVER_CERT   
);

if (!$success) {
    $error_code = mysqli_connect_errno();
    $error_msg  = mysqli_connect_error();
    
    die("Kết nối SkySQL thất bại (Error $error_code): " . $error_msg);
}

mysqli_set_charset($conn, $charset);

echo "<!-- Kết nối SkySQL thành công -->"; 