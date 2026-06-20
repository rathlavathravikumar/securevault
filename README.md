# SecureVault – Digital Sports Media Integrity

## Problem Statement
Sports organizations need a simple system to protect their official digital sports media and detect unauthorized copies. The platform should register official media, generate digital fingerprints, and simulate tracking of suspicious content.

## Solution
SecureVault is now an advanced PHP/MySQL application for sports organizations to upload official photos and videos, generate a hash-based fingerprint for every upload, and verify whether suspicious content matches registered media.

## Features
- User registration and login using PHP sessions
- Official media upload with digital fingerprint generation
- Suspicious file verification using hash comparison
- Detection logs for every content check
- Dashboard with official media, check results, and flagged content summaries
- Simple, clean UI using HTML, CSS, and JavaScript

## Tech Stack
- Frontend: HTML, CSS, JavaScript
- Backend: PHP
- Database: MySQL
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

## Online Deployment
SecureVault needs a host that supports PHP and MySQL. Static-only hosts such as GitHub Pages will not run the login, upload, or dashboard features.

### Shared PHP Hosting
1. Create a MySQL database in your hosting control panel.
2. Import `schema.sql` into that database.
3. Upload all project files to the site's public PHP folder, usually `public_html`.
4. Set the database connection values in the host's environment settings if available:
   - `DB_HOST`
   - `DB_PORT`
   - `DB_USER`
   - `DB_PASS`
   - `DB_NAME`
5. If the host does not support environment variables, update the matching fallback values in `config.php`.
6. Make sure `uploads/` and `uploads/checked/` are writable by PHP.
7. Open `/register.php` on the live domain and create the first account.

### Docker Hosting
This repo includes a `Dockerfile` for PHP/Apache hosts that support containers. Configure the same `DB_*` environment variables and attach an external MySQL database.

## Notes
- The system simulates detection using hash comparison only.
- No external API is required.
- Stored fingerprints allow fast matching of suspicious uploads.
