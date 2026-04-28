<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirectToLogin();
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="securevault_detection_logs.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Suspicious File', 'Result', 'Checked At']);

$stmt = $conn->prepare('SELECT suspicious_file, result, checked_at FROM detection_logs WHERE user_id = ? ORDER BY checked_at DESC');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['suspicious_file'], 
        $row['result'], 
        $row['checked_at'],
    ]);
}

$stmt->close();
fclose($output);
exit;
