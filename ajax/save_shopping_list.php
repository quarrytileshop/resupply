<?php
/**
 * resupply - AJAX Instant Save Handler (README-Aligned)
 * Called by shopping_list_builder.php for auto-save
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

header('Content-Type: application/json');

if (!is_logged_in() || !verify_csrf_token($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false]);
    exit;
}

// Example save logic — extend as needed
echo json_encode(['success' => true]);
?>