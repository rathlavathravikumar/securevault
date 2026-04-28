<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadedFile = $_FILES['file'];
    $fileName = basename($uploadedFile['name']);
    $fileTmpPath = $uploadedFile['tmp_name'];
    $fileSize = $uploadedFile['size'];
    $fileError = $uploadedFile['error'];

    $allowedExtensions = ['png', 'jpg', 'jpeg', 'gif', 'mp4', 'mov', 'avi'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($fileError !== UPLOAD_ERR_OK) {
        $message = 'Upload failed. Please try again.';
    } elseif (!in_array($fileExtension, $allowedExtensions)) {
        $message = 'Only image and video files are allowed.';
    } elseif ($fileSize > 50 * 1024 * 1024) {
        $message = 'File size must be less than 50MB.';
    } else {
        $fileFingerprint = isImageFile($fileTmpPath) ? computeDHash($fileTmpPath) : sha1_file($fileTmpPath);

        $checkStmt = $conn->prepare('SELECT id FROM media_files WHERE fingerprint = ? LIMIT 1');
        $checkStmt->bind_param('s', $fileFingerprint);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $message = 'This official media file is already registered.';
        } else {
            $targetDir = 'uploads/';
            $uniqueName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $fileName);
            $targetPath = $targetDir . $uniqueName;

            if (move_uploaded_file($fileTmpPath, $targetPath)) {
                $insert = $conn->prepare('INSERT INTO media_files (user_id, file_name, file_path, fingerprint) VALUES (?, ?, ?, ?)');
                $insert->bind_param('isss', $_SESSION['user_id'], $fileName, $targetPath, $fileFingerprint);
                if ($insert->execute()) {
                    header('Location: dashboard.php');
                    exit;
                }
                $insert->close();
                $message = 'Unable to save official media information.';
            } else {
                $message = 'Unable to move uploaded file.';
            }
        }
        $checkStmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVault - Upload File</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
<div class="container">
    <h1>Upload a File</h1>
    <div class="link-row">
        <a href="dashboard.php">Dashboard</a>
        <a href="check.php">Check Suspicious Content</a>
        <a href="logout.php">Logout</a>
    </div>

    <?php if ($message): ?>
        <div class="alert message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form action="upload.php" method="post" enctype="multipart/form-data" onsubmit="return validateUploadForm();">
        <input type="file" id="file" name="file" accept=".png,.jpg,.jpeg,.gif,.mp4,.mov,.avi" required>
        <button type="submit">Register Official Media</button>
    </form>
</div>
</body>
</html>
