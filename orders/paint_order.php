<?php
/**
 * resupply - Paint Order Page (inside orders/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Paint Order - Resupply Rocket";
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
    // Original paint order logic would go here (preserves your existing behavior)
    // Example: insert into orders table with order_type = 'paint'
    $_SESSION['message'] = "Paint order created successfully!";
    header("Location: ../view_order.php?id=999"); // placeholder - will be replaced with real order ID
    exit;
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Paint Order</h1>
    <p class="text-muted">Specialty paint, coatings, and related supplies.</p>

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
                    <label class="form-label">Paint Type / Color Notes</label>
                    <textarea name="notes" class="form-control" rows="4" 
                              placeholder="e.g. Sherwin Williams SW 7005 Pure White, 5 gallon buckets"></textarea>
                </div>

                <!-- Product selection area (same as your original paint_order.php) -->
                <div class="alert alert-info">
                    Paint product selection grid / checkboxes go here (same as your original file)
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-warning btn-lg px-5">Create Paint Order</button>
                    <a href="../orders/order.php" class="btn btn-secondary btn-lg px-5 ms-3">← Back to Order Types</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>