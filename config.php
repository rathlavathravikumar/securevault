<?php
session_start();

// Database connection settings
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "securevault";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function redirectToLogin()
{
    header("Location: login.php");
    exit;
}

function sanitize($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function computeDHash($imagePath) {
    // Load image
    $image = imagecreatefromstring(file_get_contents($imagePath));
    if (!$image) return false;

    // Resize to 9x8
    $resized = imagescale($image, 9, 8);
    imagedestroy($image);

    // Grayscale and get pixels
    $width = imagesx($resized);
    $height = imagesy($resized);
    $hash = '';

    for ($y = 0; $y < $height; $y++) {
        $prev = null;
        for ($x = 0; $x < $width; $x++) {
            $rgb = imagecolorat($resized, $x, $y);
            $gray = ($rgb >> 16) & 0xFF; // Red channel approx
            if ($prev !== null) {
                $hash .= ($gray > $prev) ? '1' : '0';
            }
            $prev = $gray;
        }
    }

    imagedestroy($resized);
    return $hash;
}

function hammingDistance($hash1, $hash2) {
    if (strlen($hash1) !== strlen($hash2)) return PHP_INT_MAX;
    $distance = 0;
    for ($i = 0; $i < strlen($hash1); $i++) {
        if ($hash1[$i] !== $hash2[$i]) $distance++;
    }
    return $distance;
}

function isImageFile($filePath) {
    $mime = mime_content_type($filePath);
    return in_array($mime, ['image/png', 'image/jpg', 'image/jpeg', 'image/gif']);
}
?>