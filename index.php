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
    <title>SecureVault - Secure Digital Asset Verification</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <div class="container topbar-inner">
        <a class="brand" href="index.php">SecureVault</a>
        <nav class="nav-links">
            <a href="login.php">Login</a>
            <a href="register.php" class="button button-secondary">Get started</a>
        </nav>
    </div>
</header>
<main class="page-shell container">
    <section class="hero">
        <div class="hero-left">
            <span class="eyebrow">Digital asset trust</span>
            <h1>Protect official media with enterprise-grade verification and audit-ready integrity.</h1>
            <p>Register official content, generate secure fingerprints, and detect unauthorized files across your organization from a single trusted dashboard.</p>
            <div class="hero-actions">
                <a class="button button-primary" href="register.php">Create account</a>
                <a class="button button-secondary" href="login.php">Log in</a>
            </div>
            <div class="info-panel" style="margin-top:32px;">
                <div class="status-card">
                    <span>Encrypted storage</span>
                    <h3>Verified file registry</h3>
                </div>
                <div class="status-card">
                    <span>Continuous monitoring</span>
                    <h3>Audit-ready evidence</h3>
                </div>
                <div class="status-card">
                    <span>Trusted verification</span>
                    <h3>Fast fingerprint checks</h3>
                </div>
            </div>
        </div>
        <aside class="hero-panel">
            <div class="panel-card">
                <span class="panel-label">Integrity score</span>
                <h3>Keep your official media trusted and track unauthorized access.</h3>
                <p>SecureVault uses hash-based verification to match suspicious files with registered assets instantly and generate a clear trust report.</p>
            </div>
            <div class="panel-card">
                <span class="panel-label">Suspicious content testing</span>
                <h3>Flag unauthorized copies with confidence.</h3>
                <p>Upload suspicious files, compare digital fingerprints, and generate evidence for every verification attempt.</p>
            </div>
            <div class="panel-card">
                <span class="panel-label">Audit exports</span>
                <h3>Download logs for compliance and reporting.</h3>
                <p>Export detection history to CSV for stakeholder review, audit sessions, or incident response.</p>
            </div>
        </aside>
    </section>

    <section class="intro-section">
        <div class="section-title">
            <h2>Why organizations choose SecureVault</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <h3>Official media registry</h3>
                <p>Store verified files with fingerprint metadata and keep every upload traceable in a secure vault.</p>
            </div>
            <div class="feature-card">
                <h3>Fingerprint-based verification</h3>
                <p>Use SHA fingerprint matching to confirm if suspicious content matches official assets.</p>
            </div>
            <div class="feature-card">
                <h3>Unauthorized detection</h3>
                <p>Flag suspicious uploads immediately and maintain accountability with clear results.</p>
            </div>
            <div class="feature-card">
                <h3>Trusted reports</h3>
                <p>Export audit logs for legal review, brand protection, and security operations.</p>
            </div>
        </div>
    </section>
</main>
<footer class="footer container">
    <div class="footer-inner">
        <span>SecureVault © 2026</span>
        <div class="footer-links">
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        </div>
    </div>
</footer>
</body>
</html>
