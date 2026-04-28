<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

$mediaFiles = [];
$stmt = $conn->prepare('SELECT id, file_name, file_path, fingerprint, uploaded_at FROM media_files WHERE user_id = ? ORDER BY uploaded_at DESC');
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $mediaFiles[] = $row;
}
$stmt->close();

$logs = [];
$logStmt = $conn->prepare('SELECT id, suspicious_file, result, checked_at FROM detection_logs WHERE user_id = ? ORDER BY checked_at DESC');
$logStmt->bind_param('i', $userId);
$logStmt->execute();
$logResult = $logStmt->get_result();

$authorizedCount = 0;
$unauthorizedCount = 0;
while ($row = $logResult->fetch_assoc()) {
    $logs[] = $row;
    if ($row['result'] === 'Authorized') {
        $authorizedCount++;
    } else {
        $unauthorizedCount++;
    }
}
$logStmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVault - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Welcome, <?php echo sanitize($userName); ?></h1>
    <div class="link-row">
        <a href="upload.php">Upload Official Media</a>
        <a href="check.php">Check Suspicious Content</a>
        <a href="export_logs.php">Export Detection Logs</a>
        <a href="logout.php">Logout</a>
    </div>

    <?php $integrityScore = count($logs) ? round((($authorizedCount / count($logs)) * 100), 2) : null; ?>

    <div class="summary-grid">
        <div class="card">
            <span>Official Media</span>
            <strong><?php echo count($mediaFiles); ?></strong>
        </div>
        <div class="card">
            <span>Checks Done</span>
            <strong><?php echo count($logs); ?></strong>
        </div>
        <div class="card">
            <span>Integrity Score</span>
            <strong><?php echo $integrityScore !== null ? $integrityScore . '%': 'N/A'; ?></strong>
        </div>
        <div class="card">
            <span>Unauthorized</span>
            <strong><?php echo $unauthorizedCount; ?></strong>
        </div>
    </div>

    <?php $latestLog = !empty($logs) ? $logs[0] : null; ?>
    <?php if ($latestLog): ?>
        <div class="alert info-card">
            <strong>Latest Detection:</strong> <?php echo sanitize($latestLog['suspicious_file']); ?>
            <span class="badge <?php echo $latestLog['result'] === 'Authorized' ? 'status-authorized' : 'status-unauthorized'; ?>">
                <?php echo sanitize($latestLog['result']); ?>
            </span>
            <div>Checked at <?php echo sanitize($latestLog['checked_at']); ?></div>
        </div>
    <?php endif; ?>

    <h2>Your Official Media</h2>

    <?php if (empty($mediaFiles)): ?>
        <div class="alert">You haven't registered any official media yet.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Uploaded At</th>
                    <th>Fingerprint</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mediaFiles as $file): ?>
                    <tr>
                        <td><?php echo sanitize($file['file_name']); ?></td>
                        <td><?php echo sanitize($file['uploaded_at']); ?></td>
                        <td><?php echo sanitize(substr($file['fingerprint'], 0, 16)); ?>...</td>
                        <td><a href="<?php echo sanitize($file['file_path']); ?>" target="_blank">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <h2>Detection Logs</h2>

    <?php if (empty($logs)): ?>
        <div class="alert">No suspicious content has been checked yet.</div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Checked File</th>
                    <th>Result</th>
                    <th>Checked At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo sanitize($log['suspicious_file']); ?></td>
                        <td><?php echo sanitize($log['result']); ?></td>
                        <td><?php echo sanitize($log['checked_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>
