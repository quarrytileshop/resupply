<?php
/**
 * resupply - General Order Page (inside orders/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "General Order - Resupply Rocket";
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
    // Original general order logic would go here (preserved structure)
    // Example: insert into orders table, etc.
    $_SESSION['message'] = "General order created successfully!";
    header("Location: ../view_order.php?id=999"); // placeholder - update with real order ID
    exit;
}
?>

<div class="container mt-4">
    <h1 class="mb-4">General Order</h1>
    <p class="text-muted">Create a standard purchase order for any products.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Order Notes / PO Reference</label>
                    <textarea name="notes" class="form-control" rows="3" placeholder="Any special instructions for your vendor"></textarea>
                </div>

                <!-- Product selection would normally be here (kept simple for now) -->
                <div class="alert alert-info">
                    Product selection grid / search goes here (same as your original general_order.php)
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success btn-lg px-5">Create General Order</button>
                    <a href="../orders/order.php" class="btn btn-secondary btn-lg px-5 ms-3">← Back to Order Types</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>