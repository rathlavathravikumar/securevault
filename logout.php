<?php
require_once 'config.php';

$loggedOut = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION = [];
    session_unset();
    session_destroy();
    $loggedOut = true;
}

if (!$loggedOut && !isLoggedIn()) {
    redirectToLogin();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVault - Logout</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <a class="brand" href="dashboard.php">SecureVault</a>
        <nav class="nav-links">
            <?php if (isLoggedIn()): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="upload.php">Upload</a>
                <a href="check.php">Verify</a>
                <a href="export_logs.php">Export</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
                <a href="index.php">Home</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="page-shell container">
    <section class="form-card">
        <div class="page-header">
            <span class="eyebrow">Session</span>
            <h1><?php echo $loggedOut ? 'Signed out successfully' : 'Confirm logout'; ?></h1>
            <p>
                <?php echo $loggedOut
                    ? 'Your SecureVault session has ended. You can safely close this window or sign in again to continue monitoring your assets.'
                    : 'End your current session to keep your account secure. You will be redirected to sign in again afterward.';
                ?>
            </p>
        </div>

        <?php if ($loggedOut): ?>
            <div class="alert">You are now signed out of SecureVault.</div>
            <div style="margin-top: 28px; display: flex; gap: 14px; flex-wrap: wrap;">
                <a class="button button-primary" href="login.php">Sign back in</a>
                <a class="button button-secondary" href="index.php">Return home</a>
            </div>
        <?php else: ?>
            <form action="logout.php" method="post">
                <button type="submit" class="button button-primary">Sign out securely</button>
            </form>
            <div class="form-footer">
                <span>Not ready yet?</span>
                <a href="dashboard.php">Return to dashboard</a>
            </div>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
