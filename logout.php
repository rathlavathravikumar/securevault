<?php
require_once 'config.php';

// Destroy session and redirect to login page
$_SESSION = [];
session_unset();
session_destroy();

header('Location: login.php');
exit;
