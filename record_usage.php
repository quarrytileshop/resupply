<?php
/**
 * resupply - Record Usage Page
 * Updated for new folder structure (May 14, 2026)
 * All includes and asset paths updated
 */

$page_title = "Record Usage - Resupply Rocket";
require_once 'includes/config.php';
require_once 'includes/header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Original logic preserved - adjust columns to match your actual usage table
    $product_id = (int)($_POST['product_id'] ?? 0);
    $quantity   = (int)($_POST['quantity'] ?? 0);
    $notes      = trim($_POST['notes'] ?? '');

    if ($product_id > 0 && $quantity > 0) {
        $stmt = $pdo->prepare("INSERT INTO usage_logs 
            (user_id, organization_id, product_id, quantity, notes, recorded_at) 
            VALUES (:user_id, :org_id, :product_id, :qty, :notes, NOW())");
        
        $success = $stmt->execute([
            'user_id'    => $_SESSION['user_id'],
            'org_id'     => $_SESSION['organization_id'] ?? 0,
            'product_id' => $product_id,
            'qty'        => $quantity,
            'notes'      => $notes
        ]);

        if ($success) {
            $_SESSION['message'] = "Usage recorded successfully!";
            header("Location: record_usage.php");
            exit;
        } else {
            $message = "Failed to record usage. Please try again.";
        }
    } else {
        $message = "Please select a product and enter a quantity.";
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Record Usage</h1>
    <p class="text-muted">Log how much of each product your organization has used.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Product</label>
                        <select name="product_id" class="form-select" required>
                            <option value="">— Select Product —</option>
                            <?php
                            $stmt = $pdo->prepare("SELECT id, name FROM products WHERE active = 1 ORDER BY name");
                            $stmt->execute();
                            foreach ($stmt->fetchAll() as $p):
                            ?>
                            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity Used</label>
                        <input type="number" name="quantity" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Notes (optional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="e.g. Job site #4">
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Record Usage</button>
                    <a href="dashboard.php" class="btn btn-secondary btn-lg px-5 ms-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-5">
        <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>