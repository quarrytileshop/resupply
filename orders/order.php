<?php
/**
 * resupply - Main Order Creation Page (inside orders/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and internal links updated
 */

$page_title = "New Order - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

// Simple order type selector (preserves original behavior)
$order_type = $_GET['type'] ?? 'general';
?>

<div class="container mt-4">
    <h1 class="mb-4">Create New Order</h1>
    <p class="text-muted">Choose your order type below.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- General Order -->
        <div class="col-md-4">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <img src="../assets/icons/general-order.png" alt="General" style="width:80px; height:80px;" class="mb-3">
                    <h5 class="card-title">General Order</h5>
                    <p class="card-text text-muted">Any products not covered by other categories</p>
                    <a href="general_order.php" class="btn btn-primary w-100">Create General Order</a>
                </div>
            </div>
        </div>

        <!-- Paint Order -->
        <div class="col-md-4">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <img src="../assets/icons/paint.png" alt="Paint" style="width:80px; height:80px;" class="mb-3">
                    <h5 class="card-title">Paint Order</h5>
                    <p class="card-text text-muted">Specialty paint &amp; coatings</p>
                    <a href="paint_order.php" class="btn btn-warning w-100">Create Paint Order</a>
                </div>
            </div>
        </div>

        <!-- Propane Order -->
        <div class="col-md-4">
            <div class="card h-100 text-center">
                <div class="card-body">
                    <img src="../assets/icons/propane.png" alt="Propane" style="width:80px; height:80px;" class="mb-3">
                    <h5 class="card-title">Propane Order</h5>
                    <p class="card-text text-muted">Propane tanks &amp; refills</p>
                    <a href="propane_order.php" class="btn btn-info w-100">Create Propane Order</a>
                </div>
            </div>
        </div>

        <!-- Checkbox Style Order (quick bulk) -->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5>Quick Checkbox Order</h5>
                    <p class="text-muted">Select multiple items at once</p>
                    <a href="checkbox_create.php" class="btn btn-success btn-lg px-5">Start Checkbox Order</a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <a href="../dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>