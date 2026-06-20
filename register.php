<?php
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$name || !$email || !$password) {
        $message = 'Please fill in all fields.';
    } elseif (strlen($password) < 6) {
        $message = 'Password must be at least 6 characters.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = 'This email is already registered.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
            $insert->bind_param('sss', $name, $email, $hashedPassword);
            if ($insert->execute()) {
                header('Location: login.php?registered=1');
                exit;
            } else {
                $message = 'Registration failed. Please try again.';
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVault - Register</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <a class="brand" href="index.php">SecureVault</a>
        <nav class="nav-links">
            <a href="login.php">Already have an account?</a>
            <a href="index.php">Home</a>
        </nav>
    </div>
</header>
<main class="auth-layout container">
    <section class="auth-card">
        <div class="page-header">
            <span class="eyebrow">Create your secure account</span>
            <h1>Register for SecureVault</h1>
            <p>Sign up to protect official media, verify suspicious content, and manage audit logs with confidence.</p>
        </div>

        <?php if ($message): ?>
            <div class="alert message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <form action="register.php" method="post" onsubmit="return validateRegisterForm();">
            <input type="text" id="name" name="name" placeholder="Full Name" required>
            <input type="email" id="email" name="email" placeholder="Email Address" required>
            <input type="password" id="password" name="password" placeholder="Password" required>
            <button type="submit" class="button button-primary">Register</button>
        </form>

        <div class="form-footer">
            <span>Already have an account?</span>
            <a href="login.php">Login</a>
        </div>
    </section>
</main>
</body>
</html>
