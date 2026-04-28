<?php
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $message = 'Please enter your email and password.';
    } else {
        $stmt = $conn->prepare('SELECT id, name, password FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userId, $userName, $hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $userName;
                $_SESSION['user_email'] = $email;
                header('Location: dashboard.php');
                exit;
            }
        }

        $message = 'Invalid email or password.';
        $stmt->close();
    }
}

$registered = isset($_GET['registered']) && $_GET['registered'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVault - Login</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
<div class="container">
    <h1>SecureVault</h1>
    <h2>Sign in</h2>

    <?php if ($registered): ?>
        <div class="alert">Registration successful. Please login.</div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="alert message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form action="login.php" method="post" onsubmit="return validateLoginForm();">
        <input type="email" id="email" name="email" placeholder="Email Address" required>
        <input type="password" id="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>

    <div class="link-row">
        <a href="index.php">Home</a>
        <span>New to SecureVault?</span>
        <a href="register.php">Register</a>
    </div>
</div>
</body>
</html>
