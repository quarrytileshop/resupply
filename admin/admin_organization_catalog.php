<?php
/**
 * resupply - Admin Organization Catalog Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Organization Catalog - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$organization_id = (int)($_GET['org_id'] ?? 0);
if ($organization_id <= 0) {
    $_SESSION['error'] = "Invalid organization ID.";
    header("Location: admin_organizations.php");
    exit;
}

// Fetch organization name
$stmt = $pdo->prepare("SELECT name FROM organizations WHERE id = :id");
$stmt->execute(['id' => $organization_id]);
$org = $stmt->fetch();

if (!$org) {
    $_SESSION['error'] = "Organization not found.";
    header("Location: admin_organizations.php");
    exit;
}

// Fetch catalog/products for this organization (preserves original logic)
$stmt = $pdo->prepare("SELECT p.* FROM products p 
                       ORDER BY p.category, p.name");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-4">Catalog for <?= htmlspecialchars($org['name']) ?></h1>
    <p class="text-muted">Products visible to this organization.</p>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

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
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-muted">No products in the catalog yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <a href="admin_organizations.php" class="btn btn-secondary">← Back to Organizations</a>
        <a href="admin_catalog.php" class="btn btn-outline-primary ms-3">Global Catalog</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>