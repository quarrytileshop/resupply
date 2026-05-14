<?php
/**
 * resupply - Shopping List Builder Page
 * Updated for new folder structure (May 14, 2026)
 * All includes, asset paths, and internal links updated
 */

$page_title = "Build Shopping List - Resupply Rocket";
require_once 'includes/config.php';
require_once 'includes/header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$organization_id = $_SESSION['organization_id'] ?? 0;
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<div class="container mt-4">
    <h1 class="mb-4">Build New Shopping List</h1>
    <p class="text-muted">Select products below to create a custom shopping list for your organization.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" action="save_shopping_list.php">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Available Products</h5>
                    </div>
                    <div class="card-body">
                        <!-- Product selection grid (original logic preserved - adjust query to your actual products table) -->
                        <?php
                        $stmt = $pdo->prepare("SELECT * FROM products WHERE active = 1 ORDER BY category, name");
                        $stmt->execute();
                        $products = $stmt->fetchAll();
                        ?>
                        
                        <div class="row g-3">
                            <?php foreach ($products as $product): ?>
                            <div class="col-md-4 col-lg-3">
                                <div class="card h-100 border">
                                    <img src="assets/product-images/<?= htmlspecialchars($product['image'] ?? 'placeholder.jpg') ?>" 
                                         class="card-img-top" style="height:120px; object-fit:cover;" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= htmlspecialchars($product['name']) ?></h6>
                                        <p class="card-text small text-muted"><?= htmlspecialchars($product['category'] ?? '') ?></p>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="products[<?= $product['id'] ?>]" 
                                                   class="form-control text-end" value="0" min="0" step="1">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">List Name</label>
                    <input type="text" name="list_name" class="form-control" placeholder="e.g. Monthly Tile Restock" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Description (optional)</label>
                    <input type="text" name="description" class="form-control" placeholder="Notes for your vendor">
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-success btn-lg px-5">Save Shopping List</button>
            <a href="shopping_lists.php" class="btn btn-secondary btn-lg px-5 ms-3">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>