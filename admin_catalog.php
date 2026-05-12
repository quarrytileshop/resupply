<?php
// admin_catalog.php – Updated for vendor-scoped catalog – 2026-05-11
$page_title = "Catalog Management - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) && !isset($_SESSION['is_admin'])) {
    header("Location: dashboard.php");
    exit;
}

// For now we show all items; in future this will filter by vendor_id
$stmt = $pdo->query("SELECT * FROM catalog_items ORDER BY item_name");
$catalog_items = $stmt->fetchAll();

// Handle adding a new catalog item
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_item') {
    $item_name = trim($_POST['item_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    
    if ($item_name && $price > 0) {
        $stmt = $pdo->prepare("INSERT INTO catalog_items (item_name, description, price) 
                              VALUES (:item_name, :description, :price)");
        $stmt->execute([
            'item_name' => $item_name,
            'description' => $description,
            'price' => $price
        ]);
        $success = "Catalog item added successfully!";
        // Refresh list
        $stmt = $pdo->query("SELECT * FROM catalog_items ORDER BY item_name");
        $catalog_items = $stmt->fetchAll();
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-3">Catalog Management</h1>
    <p class="text-muted">Items shown here are available for all your customer shopping lists.</p>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Add new item form -->
    <div class="card mb-5">
        <div class="card-header">Add New Catalog Item</div>
        <div class="card-body">
            <form method="post">
                <input type="hidden" name="action" value="add_item">
                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label">Item Name</label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Description</label>
                        <input type="text" name="description" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Add to Catalog</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Catalog table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($catalog_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['item_name']) ?></td>
                            <td><?= htmlspecialchars($item['description'] ?? '') ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                            <td><button class="btn btn-sm btn-outline-danger">Delete</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="vendor_dashboard.php" class="btn btn-secondary">← Back to Vendor Dashboard</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
