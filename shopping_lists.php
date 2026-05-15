<?php
/**
 * resupply - Shopping Lists Page
 * Updated for new folder structure (May 14, 2026)
 * Removed duplicate container (header.php already provides it)
 */

$page_title = "My Shopping Lists - Resupply Rocket";
require_once 'includes/config.php';
require_once 'includes/header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit;
}

$organization_id = $_SESSION['organization_id'] ?? 0;

// Fetch user's organization's shopping lists
$stmt = $pdo->prepare("SELECT * FROM shopping_lists WHERE organization_id = :org_id ORDER BY name");
$stmt->execute(['org_id' => $organization_id]);
$lists = $stmt->fetchAll();
?>

<h1 class="mb-3">My Shopping Lists</h1>
<p class="text-muted">These lists were built by your vendor for quick reordering.</p>

<?php if (empty($lists)): ?>
    <div class="alert alert-info">No shopping lists yet. Your vendor can create them for you.</div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($lists as $list): ?>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5><?= htmlspecialchars($list['name']) ?></h5>
                    <p class="text-muted"><?= htmlspecialchars($list['description'] ?? '') ?></p>
                    <a href="orders/general_order.php?list_id=<?= $list['id'] ?>" class="btn btn-primary w-100">Use This List →</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="mt-5">
    <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
</div>

<?php require_once 'includes/footer.php'; ?>