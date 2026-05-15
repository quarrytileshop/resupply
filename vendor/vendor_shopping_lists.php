<?php
/**
 * resupply - Vendor Shopping Lists Page (Professional Rewrite)
 * View of all shopping lists from their organizations.
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

if (!is_vendor()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$page_title = 'Shopping Lists';

require_once '../includes/header.php';
?>

<h1 class="mb-4">All Shopping Lists</h1>

<div class="alert alert-info">Lists from all connected organizations appear here.</div>

<?php require_once '../includes/footer.php'; ?>