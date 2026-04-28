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
<div class="container">
    <h1>SecureVault</h1>
    <h2>Create an account</h2>

    <?php if ($message): ?>
        <div class="alert message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form action="register.php" method="post" onsubmit="return validateRegisterForm();">
        <input type="text" id="name" name="name" placeholder="Full Name" required>
        <input type="email" id="email" name="email" placeholder="Email Address" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <button type="submit">Register</button>
    </form>

    <div class="link-row">
        <a href="index.php">Home</a>
        <span>Already have an account?</span>
        <a href="login.php">Login</a>
    </div>
</div>
</body>
</html>
