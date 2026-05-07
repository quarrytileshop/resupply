<?php

// logout_php

session_start();

// Destroy the session
session_unset();
session_destroy();

// Clear cookies
setcookie(session_name(), '', time() - 3600, '/');

// Redirect to login page
header("Location: login.php");
exit;
?>
