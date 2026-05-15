<?php
/**
 * resupply - Save Order Handler (FINAL README-Aligned)
 * Handles all 4 order types, rocket animation feedback, and professional emails
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

if (!is_logged_in() || !verify_csrf_token($_POST['csrf_token'] ?? '')) {
    $_SESSION['error'] = 'Security check failed.';
    header("Location: " . BASE_URL . "orders/order.php");
    exit;
}

$order_type = $_POST['order_type'] ?? 'general';
$list_id    = $_POST['list_id'] ?? null;
$items      = $_POST['items'] ?? '';

// Save order (multi-tenant safe)
$stmt = $pdo->prepare("
    INSERT INTO orders (user_id, organization_id, vendor_id, order_type, items, status, created_at)
    VALUES (?, ?, ?, ?, ?, 'pending', NOW())
");
$stmt->execute([$_SESSION['user_id'], $_SESSION['organization_id'] ?? 0, $_SESSION['vendor_id'] ?? 0, $order_type, $items]);

$order_id = $pdo->lastInsertId();

// Type-specific email logic
if ($order_type === 'checkbox') {
    // Special email to Russell (no vendor PO)
    send_email('russell@quarrytileshop.com', "Checkbox Order #{$order_id}", "<h2>New Checkbox Order</h2><p>From: {$_SESSION['email']}</p><p>Items: {$items}</p>");
} else {
    // Professional PO email to vendor with rocket animation in HTML
    $html_body = file_get_contents('../includes/email_templates/po_template.php');
    $html_body = str_replace(['{{ORDER_ID}}', '{{ORDER_TYPE}}', '{{ITEMS}}', '{{USER_EMAIL}}'], 
                             [$order_id, ucfirst($order_type), $items, $_SESSION['email']], $html_body);
    send_po_email('vendor@quarrytileshop.com', 'Quarry Tile Vendor', "Purchase Order #{$order_id} – {$order_type}", $html_body);
}

$_SESSION['message'] = '🚀 Order #' . $order_id . ' sent successfully!';
header("Location: " . BASE_URL . "history.php");
exit;
?>