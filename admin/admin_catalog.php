<?php
/**
 * resupply - Admin Catalog Management Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Manage Catalog - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

// Fetch all products (preserves original logic - adjust query to your actual products table)
$stmt = $pdo->prepare("SELECT * FROM products ORDER BY category, name");
$stmt->execute();
$products = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    // Example: toggle active status or bulk actions (original behavior preserved)
    if ($_POST['action'] === 'toggle_active') {
        $product_id = (int)$_POST['product_id'];
        $stmt = $pdo->prepare("UPDATE products SET active = NOT active WHERE id = :id");
        $stmt->execute(['id' => $product_id]);
        $_SESSION['message'] = "Product status updated.";
        header("Location: admin_catalog.php");
        exit;
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Catalog Management</h1>
    <p class="text-muted">Add, edit, or deactivate products visible to customers and vendors.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="mb-3">
        <a href="#" class="btn btn-success" onclick="alert('Add new product form would go here (same as your original catalog logic)');">+ Add New Product</a>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if ($products): ?>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($product['image'])): ?>
                                        <img src="../assets/product-images/<?= htmlspecialchars($product['image']) ?>" 
                                             style="width:50px; height:50px; object-fit:cover;" alt="">
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['category'] ?? '—') ?></td>
                                <td>$<?= number_format($product['price'] ?? 0, 2) ?></td>
                                <td>
                                    <span class="badge bg-<?= $product['active'] ? 'success' : 'secondary' ?>">
                                        <?= $product['active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="action" value="toggle_active">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Toggle Status</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No products in catalog yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>