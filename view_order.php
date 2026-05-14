<?php
/**
 * resupply - View Order (Root Level)
 * Updated for new folder structure (May 14, 2026)
 * Simple redirect to the detailed version in the orders/ folder
 * This preserves all old links and bookmarks
 */

require_once 'includes/config.php';

$order_id = (int)($_GET['id'] ?? 0);
if ($order_id > 0) {
    header("Location: orders/view_order.php?id=" . $order_id);
} else {
    header("Location: history.php");
}
exit;
?>