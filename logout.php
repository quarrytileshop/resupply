<?php
// logout.php – Modified 2026-05-08 – Lines: 25
require_once 'config.php';
session_start();

// Destroy the entire session
session_unset();
session_destroy();

// Redirect to login
header("Location: login.php");
exit;
?>
