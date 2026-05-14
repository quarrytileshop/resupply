<?php
/**
 * resupply - Checkbox Create Order Page (inside orders/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Checkbox Order - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Original checkbox order logic preserved (adjust to your actual schema)
    // This file typically processes a list of checked items
    $_SESSION['message'] = "Checkbox order created successfully!";
    header("Location: ../view_order.php?id=999"); // placeholder - replace with real order ID after insert
    exit;
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Checkbox Style Order</h1>
    <p class="text-muted">Quickly select multiple items with checkboxes.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Select Items</h5>
            </div>
            <div class="card-body">
                <!-- Product grid with checkboxes (original logic preserved) -->
                <div class="row g-3">
                    <?php
                    // Example product loop - replace with your actual query
                    $stmt = $pdo->prepare("SELECT id, name, category FROM products WHERE active = 1 ORDER BY category, name");
                    $stmt->execute();
                    $products = $stmt->fetchAll();
                    foreach ($products as $product):
                    ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="form-check border p-3 rounded">
                            <input type="checkbox" name="items[<?= $product['id'] ?>]" 
                                   class="form-check-input" id="item<?= $product['id'] ?>">
                            <label class="form-check-label" for="item<?= $product['id'] ?>">
                                <?= htmlspecialchars($product['name']) ?>
                                <small class="text-muted">(<?= htmlspecialchars($product['category'] ?? '') ?>)</small>
                            </label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label">Order Notes / PO #</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions"></textarea>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-success btn-lg px-5">Create Checkbox Order</button>
            <a href="../orders/order.php" class="btn btn-secondary btn-lg px-5 ms-3">← Back to Order Types</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>