<?php
/**
 * resupply - Save Shopping List Handler
 * Updated for new folder structure (May 14, 2026)
 * All includes and redirects updated
 */

require_once 'includes/config.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $list_name   = trim($_POST['list_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $products    = $_POST['products'] ?? [];   // array of product_id => quantity
    $organization_id = $_SESSION['organization_id'] ?? 0;

    if (empty($list_name) || $organization_id == 0) {
        $_SESSION['error'] = "List name and organization are required.";
        header("Location: shopping_list_builder.php");
        exit;
    }

    // Insert the shopping list (preserves original table structure)
    $stmt = $pdo->prepare("INSERT INTO shopping_lists 
        (organization_id, name, description, created_by, created_at) 
        VALUES (:org_id, :name, :description, :created_by, NOW())");
    
    $stmt->execute([
        'org_id'      => $organization_id,
        'name'        => $list_name,
        'description' => $description,
        'created_by'  => $_SESSION['user_id']
    ]);

    $list_id = $pdo->lastInsertId();

    // Insert list items if any products were selected
    if (!empty($products)) {
        $stmt = $pdo->prepare("INSERT INTO shopping_list_items 
            (list_id, product_id, quantity) VALUES (:list_id, :product_id, :qty)");
        
        foreach ($products as $product_id => $qty) {
            if ((int)$qty > 0) {
                $stmt->execute([
                    'list_id'    => $list_id,
                    'product_id' => (int)$product_id,
                    'qty'        => (int)$qty
                ]);
            }
        }
    }

    $_SESSION['message'] = "Shopping list '<strong>" . htmlspecialchars($list_name) . "</strong>' has been saved successfully!";
    
    // Redirect back to the list view
    header("Location: shopping_lists.php");
    exit;
}

// If someone hits this page directly, send them back
header("Location: shopping_list_builder.php");
exit;
?>