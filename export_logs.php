<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

if (isset($_GET['download']) && $_GET['download'] === '1') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="securevault_detection_logs.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Suspicious File', 'Result', 'Checked At', 'Fingerprint']);

    $stmt = $conn->prepare('SELECT suspicious_name, result, checked_at, fingerprint FROM detection_logs WHERE user_id = ? ORDER BY checked_at DESC');
    $stmt->bind_param('i', $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['suspicious_name'],
            $row['result'],
            $row['checked_at'],
            $row['fingerprint'],
        ]);
    }

    $stmt->close();
    fclose($output);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVault - Export Logs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <a class="brand" href="dashboard.php">SecureVault</a>
        <nav class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="upload.php">Upload</a>
            <a href="check.php">Verify</a>
            <a href="logout.php" class="button button-secondary">Logout</a>
        </nav>
    </div>
</header>
<main class="page-shell container">
    <section class="form-card">
        <div class="page-header">
            <span class="eyebrow">Audit export</span>
            <h1>Export your detection logs</h1>
            <p>Download a complete CSV of your suspicious content checks and verification results for reporting or compliance review.</p>
        </div>
        <p>SecureVault keeps a detailed log of every verification attempt. Export your history anytime and share it with your team or auditors.</p>
        <div style="margin-top: 28px; display: flex; gap: 14px; flex-wrap: wrap;">
            <a class="button button-primary" href="export_logs.php?download=1">Download CSV</a>
            <a class="button button-secondary" href="dashboard.php">Return to dashboard</a>
        </div>
    </section>
</main>
</body>
</html>
