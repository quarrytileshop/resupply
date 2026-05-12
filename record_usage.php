<?php
// record_usage.php – Logs monthly usage for billing (switched OFF) – 2026-05-12
require_once 'config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$organization_id = $_SESSION['organization_id'] ?? 0;
$vendor_id       = $_SESSION['vendor_id'] ?? 0;
$type            = $_POST['type'] ?? ''; // 'order' or 'checkbox'

if ($organization_id && $vendor_id && in_array($type, ['order', 'checkbox'])) {
    $month = date('Y-m-01');

    // Upsert monthly usage record
    $stmt = $pdo->prepare("
        INSERT INTO monthly_usage (organization_id, vendor_id, usage_month, orders_count, checkbox_uses, last_activity)
        VALUES (:org_id, :vendor_id, :month, 
                IF(:type='order', 1, 0),
                IF(:type='checkbox', 1, 0),
                NOW())
        ON DUPLICATE KEY UPDATE
            orders_count   = orders_count   + IF(:type='order', 1, 0),
            checkbox_uses  = checkbox_uses  + IF(:type='checkbox', 1, 0),
            last_activity  = NOW()
    ");
    $stmt->execute([
        'org_id'   => $organization_id,
        'vendor_id'=> $vendor_id,
        'month'    => $month,
        'type'     => $type
    ]);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
