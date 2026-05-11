<?php
// logout.php – Modified 2026-05-08 – Lines: 25
require_once 'config.php';
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.php");
exit;
?>
