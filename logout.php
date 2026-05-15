<?php
require_once 'includes/config.php';

if (is_logged_in()) {
    logUsage($_SESSION['user_id'], $_SESSION['organization_id'] ?? 0, $_SESSION['vendor_id'] ?? 0, 'logout');
    session_destroy();
}

header("Location: " . BASE_URL . "login.php");
exit;
?>