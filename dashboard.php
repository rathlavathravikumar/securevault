<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

$userId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

$mediaFiles = [];
$stmt = $conn->prepare('SELECT id, file_name, file_path, fingerprint, uploaded_at FROM media_files WHERE owner_id = ? ORDER BY uploaded_at DESC');
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $mediaFiles[] = $row;
}
$stmt->close();

$logs = [];
$logStmt = $conn->prepare('SELECT id, suspicious_name, suspicious_path, result, checked_at FROM detection_logs WHERE user_id = ? ORDER BY checked_at DESC');
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

$integrityScore = count($logs) ? round((($authorizedCount / count($logs)) * 100), 2) : null;
$latestLog = !empty($logs) ? $logs[0] : null;
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
<header class="topbar">
    <div class="container topbar-inner">
        <a class="brand" href="dashboard.php">SecureVault</a>
        <nav class="nav-links">
            <a href="upload.php">Upload</a>
            <a href="check.php">Verify</a>
            <a href="export_logs.php">Export</a>
            <a href="logout.php" class="button button-secondary">Logout</a>
        </nav>
    </div>
</header>
<main class="page-shell container">
    <div class="app-grid">
        <aside class="sidebar">
            <h2>Navigation</h2>
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="active">Overview</a>
                <a href="upload.php">Upload Official Media</a>
                <a href="check.php">Check Suspicious Content</a>
                <a href="export_logs.php">Export Logs</a>
                <a href="logout.php">Logout</a>
            </nav>
        </aside>
        <section class="dashboard-main">
            <div class="dashboard-header">
                <div>
                    <p class="eyebrow">Dashboard</p>
                    <h1>Welcome back, <?php echo sanitize($userName); ?></h1>
                    <p>Monitor your verified assets, review suspicious checks, and keep your media integrity score high.</p>
                </div>
            </div>
            <div class="metrics-grid">
                <div class="metric-card">
                    <span>Official media</span>
                    <strong><?php echo count($mediaFiles); ?></strong>
                </div>
                <div class="metric-card">
                    <span>Checks completed</span>
                    <strong><?php echo count($logs); ?></strong>
                </div>
                <div class="metric-card">
                    <span>Integrity score</span>
                    <strong><?php echo $integrityScore !== null ? $integrityScore . '%' : 'N/A'; ?></strong>
                </div>
                <div class="metric-card">
                    <span>Unauthorized flags</span>
                    <strong><?php echo $unauthorizedCount; ?></strong>
                </div>
            </div>
            <?php if ($latestLog): ?>
                <div class="status-card">
                    <div class="section-title">
                        <h2>Latest detection</h2>
                    </div>
                    <p><strong><?php echo sanitize($latestLog['suspicious_name']); ?></strong> was checked on <?php echo sanitize($latestLog['checked_at']); ?>.</p>
                    <span class="badge <?php echo $latestLog['result'] === 'Authorized' ? 'status-authorized' : 'status-unauthorized'; ?>">
                        <?php echo sanitize($latestLog['result']); ?>
                    </span>
                </div>
            <?php endif; ?>
            <div class="grid-2">
                <div class="table-card">
                    <div class="page-header">
                        <div class="section-title"><h2>Your official media</h2></div>
                    </div>
                    <?php if (empty($mediaFiles)): ?>
                        <div class="alert">No official media is registered yet. Upload your first file to start verifying suspicious content.</div>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th>Uploaded</th>
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
                </div>
                <div class="activity-card">
                    <div class="section-title"><h2>Detection activity</h2></div>
                    <?php if (empty($logs)): ?>
                        <div class="alert">No suspicious checks have been completed. Use the verify page to start monitoring and protect your assets.</div>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>File</th>
                                    <th>Result</th>
                                    <th>Checked</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($logs, 0, 6) as $log): ?>
                                    <tr>
                                        <td><?php echo sanitize($log['suspicious_name']); ?></td>
                                        <td><span class="badge <?php echo $log['result'] === 'Authorized' ? 'status-authorized' : 'status-unauthorized'; ?>"><?php echo sanitize($log['result']); ?></span></td>
                                        <td><?php echo sanitize($log['checked_at']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</main>
</body>
</html>
