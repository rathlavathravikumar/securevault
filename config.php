<?php
session_start();

// Database connection settings. Defaults keep local XAMPP setup working.
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'securevault';
$db_port = (int) (getenv('DB_PORT') ?: 3306);

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function redirectToLogin(): void
{
    header('Location: login.php');
    exit;
}

function sanitize(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
