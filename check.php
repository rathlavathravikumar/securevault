<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$message = '';
$resultText = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['suspicious_file'])) {
    $uploadedFile = $_FILES['suspicious_file'];
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
        $fingerprint = isImageFile($fileTmpPath) ? computeDHash($fileTmpPath) : sha1_file($fileTmpPath);

        $checkStmt = $conn->prepare(
            'SELECT mf.file_name, mf.fingerprint, u.name FROM media_files mf JOIN users u ON mf.user_id = u.id'
        );
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        $matchFound = false;
        $officialName = '';
        $ownerName = '';
        while ($row = $result->fetch_assoc()) {
            $storedHash = $row['fingerprint'];
            $match = false;
            if (isImageFile($fileTmpPath) && strlen($storedHash) == 64) {
                $dist = hammingDistance($fingerprint, $storedHash);
                if ($dist <= 10) $match = true;
            } elseif ($fingerprint == $storedHash) {
                $match = true;
            }
            if ($match) {
                $matchFound = true;
                $officialName = $row['file_name'];
                $ownerName = $row['name'];
                break;
            }
        }
        $checkStmt->close();

        $targetDir = 'uploads/checked/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $uniqueName = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $fileName);
        $targetPath = $targetDir . $uniqueName;

        if (move_uploaded_file($fileTmpPath, $targetPath)) {
            $resultText = $matchFound ? 'Authorized' : 'Potential Unauthorized Content';
            $message = $matchFound
                ? "This suspicious media matches official content '{$officialName}' registered by {$ownerName}."
                : 'No matching official content was found. Potential unauthorized content.';

            $insert = $conn->prepare(
                'INSERT INTO detection_logs (user_id, suspicious_file, result) VALUES (?, ?, ?)'
            );
            $insert->bind_param('iss', $_SESSION['user_id'], $fileName, $resultText);
            $insert->execute();
            $insert->close();
        } else {
            $message = 'Unable to move uploaded suspicious file.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVault - Check Suspicious Content</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js" defer></script>
</head>
<body>
<div class="container">
    <h1>Check Suspicious Content</h1>
    <div class="link-row">
        <a href="dashboard.php">Dashboard</a>
        <a href="upload.php">Upload Official Media</a>
        <a href="logout.php">Logout</a>
    </div>

    <?php if ($message): ?>
        <div class="alert message"><?php echo sanitize($message); ?></div>
    <?php endif; ?>

    <?php if ($resultText): ?>
        <div class="alert">
            <strong>Result:</strong> <?php echo sanitize($resultText); ?>
        </div>
    <?php endif; ?>
    <?php if ($message && $resultText === 'Authorized'): ?>
        <div class="badge status-authorized">Match found with official content</div>
    <?php elseif ($message && $resultText === 'Potential Unauthorized Content'): ?>
        <div class="badge status-unauthorized">No match found</div>
    <?php endif; ?>

    <form action="check.php" method="post" enctype="multipart/form-data" onsubmit="return validateCheckForm();">
        <input type="file" id="suspicious_file" name="suspicious_file" accept=".png,.jpg,.jpeg,.gif,.mp4,.mov,.avi" required>
        <button type="submit">Verify Suspicious File</button>
    </form>
</div>
</body>
</html>
