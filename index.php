<?php
require_once 'config.php';

if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecureVault - Protect Digital Sports Media</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="hero-landing">
    <div class="hero-content container">
        <div class="hero-copy">
            <span class="eyebrow">SecureVault</span>
            <h1>Protect the Integrity of Digital Sports Media</h1>
            <p>Register official media, generate unique digital fingerprints, and detect unauthorized copies with fast hash-based tracking.</p>
            <div class="hero-buttons">
                <a class="primary-button" href="login.php">Login</a>
                <a class="secondary-button" href="register.php">Sign Up</a>
            </div>
        </div>
        <div class="hero-panel">
            <div class="feature-card highlighted">
                <h3>Match suspicious content instantly</h3>
                <p>Upload any suspicious file and verify it against the official media registry using secure hashing.</p>
            </div>
            <div class="feature-card">
                <h3>Integrity Score</h3>
                <p>Track detection results and see a visual measure of your media trustworthiness.</p>
            </div>
            <div class="feature-card">
                <h3>Audit-ready logs</h3>
                <p>Export detection records to CSV for reporting and stakeholder review.</p>
            </div>
        </div>
    </div>
</div>

<div class="container intro-section">
    <h2>Why SecureVault stands out</h2>
    <div class="features-grid">
        <div class="feature-card">
            <h3>Official Media Registry</h3>
            <p>Sports organizations can register photos and videos as verified official content.</p>
        </div>
        <div class="feature-card">
            <h3>Fingerprint Tracking</h3>
            <p>Each upload receives a unique SHA-1 fingerprint for reliable verification.</p>
        </div>
        <div class="feature-card">
            <h3>Unauthorized Detection</h3>
            <p>The system flags content that does not match any registered media as suspicious.</p>
        </div>
        <div class="feature-card">
            <h3>Professional Reporting</h3>
            <p>Export logs for evidence-based audits, brand protection, or incident reviews.</p>
        </div>
    </div>
</div>
</body>
</html>
