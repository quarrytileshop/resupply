<?php
// save_shopping_list.php – Updated with vendor_id isolation – 2026-05-11
require_once 'config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$vendor_id = $_SESSION['vendor_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $org_id = (int)($_POST['org_id'] ?? 0);
    $list_name = trim($_POST['list_name'] ?? '');
    $item_ids = $_POST['item_ids'] ?? [];

    if ($org_id && $list_name && !empty($item_ids)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO shopping_lists (organization_id, name, vendor_id, created_by) 
                                  VALUES (:org_id, :name, :vendor_id, :created_by)");
            $stmt->execute([
                'org_id' => $org_id,
                'name' => $list_name,
                'vendor_id' => $vendor_id,
                'created_by' => $_SESSION['user_id']
            ]);
            $list_id = $pdo->lastInsertId();

            $stmt = $pdo->prepare("INSERT INTO shopping_list_items (shopping_list_id, catalog_item_id) VALUES (:list_id, :item_id)");
            foreach ($item_ids as $item_id) {
                $stmt->execute(['list_id' => $list_id, 'item_id' => (int)$item_id]);
            }

            echo json_encode(['success' => true, 'message' => 'Shopping list saved successfully!']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
