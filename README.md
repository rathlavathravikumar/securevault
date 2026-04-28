# SecureVault – Digital Sports Media Integrity

## Problem Statement
Sports organizations need a simple system to protect their official digital sports media and detect unauthorized copies. The platform should register official media, generate digital fingerprints, and simulate tracking of suspicious content.

## Solution
SecureVault is now an advanced PHP/MySQL application for sports organizations to upload official photos and videos, generate a hash-based fingerprint for every upload, and verify whether suspicious content matches registered media.

## Features
- User registration and login using PHP sessions
- Official media upload with digital fingerprint generation
- Suspicious file verification using hash comparison and perceptual hashing for images
- Detection logs for every content check
- Dashboard with official media, check results, and flagged content summaries
- Simple, clean UI using HTML, CSS, and JavaScript
- Export detection logs to CSV

## Tech Stack
- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
- Hashing: SHA-1 for videos, dHash for images with Hamming distance similarity detection

## Objective
Develop a scalable, innovative solution to identify, track, and flag unauthorized use or misappropriation of official sports media across the internet. Enable organizations to proactively authenticate their digital assets and detect anomalies in content propagation in near real-time.

## Future Enhancements
- Integration with reverse image search APIs for internet-wide monitoring
- Video perceptual hashing
- Real-time web scraping
- API for automated checks
- Server: XAMPP / Apache

## Project Structure
- `config.php` – database connection and session helper
- `register.php` – user registration page
- `login.php` – user login page
- `dashboard.php` – overview of official media, detection metrics, and logs
- `upload.php` – register official sports media
- `index.php` – polished landing page with project branding
- `check.php` – verify suspicious content against fingerprints
- `export_logs.php` – download detection logs as CSV
- `logout.php` – logs out the current user
- `schema.sql` – one-click database setup script
- `style.css` – UI styling
- `script.js` – form validation scripts
- `uploads/` – official media storage
- `uploads/checked/` – suspicious files storage

## Standout Features
- Digital fingerprinting for every official media upload
- Simulation of unauthorized content detection using hash matching
- Detection history with authorized/unauthorized flags
- Dashboard integrity score and latest detection summary
- CSV export for professional reporting

## Database Schema
Run these SQL commands in MySQL:

```sql
CREATE DATABASE securevault;
USE securevault;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE media_files (
  id INT AUTO_INCREMENT PRIMARY KEY,
  owner_id INT NOT NULL,
  file_name VARCHAR(255) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  fingerprint VARCHAR(255) NOT NULL,
  uploaded_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (owner_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE detection_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  suspicious_name VARCHAR(255) NOT NULL,
  suspicious_path VARCHAR(255) NOT NULL,
  fingerprint VARCHAR(255) NOT NULL,
  result VARCHAR(50) NOT NULL,
  checked_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## How It Detects Unauthorized Content
When a suspicious media file is uploaded via `check.php`, the system computes a SHA-1 fingerprint for the file and compares it to stored fingerprints of official media files. If a match exists, the content is marked as `Authorized`; otherwise, it is flagged as `Potential Unauthorized Content`.

## Setup Instructions
1. Copy the project folder to `C:\xampp\htdocs\securevault`.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`.
4. Create the `securevault` database and run the SQL schema above.
5. Ensure the `uploads/` and `uploads/checked/` folders exist and are writable.
6. Copy `schema.sql` into the SQL tab and run it for one-click setup.
7. Open `http://localhost/securevault/register.php` to create an account.
8. Login at `http://localhost/securevault/login.php`.
9. Use `Upload Official Media` to register official sports content.
10. Use `Check Suspicious Content` to verify potential unauthorized media.
11. Use `Export Detection Logs` for reporting and audit trails.

## Notes
- The system simulates detection using hash comparison only.
- No external API is required.
- Stored fingerprints allow fast matching of suspicious uploads.
