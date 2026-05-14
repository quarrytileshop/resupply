<?php
/**
 * resupply - Send PO Email Handler
 * Updated for new folder structure (May 14, 2026)
 * All includes updated to new locations
 */

require_once 'includes/config.php';
require_once 'includes/email_functions.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id     = (int)($_POST['order_id'] ?? 0);
    $vendor_email = trim($_POST['vendor_email'] ?? '');
    $vendor_name  = trim($_POST['vendor_name'] ?? '');
    $subject      = trim($_POST['subject'] ?? 'Purchase Order from Quarry Tile Shop');
    $html_body    = $_POST['html_body'] ?? '';

    if ($order_id > 0 && $vendor_email && $html_body) {
        $success = send_po_email($vendor_email, $vendor_name, $subject, $html_body);

        if ($success) {
            $_SESSION['message'] = "Purchase Order email sent successfully to " . htmlspecialchars($vendor_name) . "!";
            
            // Update order status if you have a status field (preserves original behavior)
            $stmt = $pdo->prepare("UPDATE orders SET status = 'sent' WHERE id = :id");
            $stmt->execute(['id' => $order_id]);
            
            header("Location: orders/view_order.php?id=" . $order_id);
            exit;
        } else {
            $_SESSION['error'] = "Failed to send email. Please try again or contact support.";
        }
    } else {
        $_SESSION['error'] = "Missing required information to send PO.";
    }
}

// Fallback if someone hits this page directly
header("Location: dashboard.php");
exit;
?>