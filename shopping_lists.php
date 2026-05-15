<?php
/**
 * resupply - Shopping Lists Page (README-Aligned Rewrite)
 * Now displays assigned lists as SCROLLABLE TABS per README
 * Date: May 15, 2026
 */

require_once 'includes/config.php';

$page_title = 'Shopping Lists';

require_once 'includes/header.php';

// Fetch user's/organization's shopping lists (multi-tenant safe)
$stmt = $pdo->prepare("
    SELECT * FROM shopping_lists 
    WHERE (organization_id = ? OR user_id = ?) 
    ORDER BY name ASC
");
$stmt->execute([$_SESSION['organization_id'] ?? 0, $_SESSION['user_id']]);
$lists = $stmt->fetchAll();
?>

<h1 class="mb-4">Your Shopping Lists</h1>

<!-- Scrollable Tabs (exactly as described in README) -->
<div class="nav nav-tabs mb-4 flex-nowrap overflow-auto" id="listTabs" role="tablist">
    <?php foreach ($lists as $i => $list): ?>
        <button class="nav-link <?= $i === 0 ? 'active' : '' ?>" 
                id="tab-<?= $list['id'] ?>" 
                data-bs-toggle="tab" 
                data-bs-target="#list-<?= $list['id'] ?>" 
                type="button">
            <?= htmlspecialchars($list['name']) ?>
        </button>
    <?php endforeach; ?>
    <?php if (empty($lists)): ?>
        <button class="nav-link active">No Lists Yet</button>
    <?php endif; ?>
</div>

<div class="tab-content">
    <?php if (empty($lists)): ?>
        <div class="alert alert-info">
            No shopping lists yet. <a href="<?= BASE_URL ?>shopping_list_builder.php" class="btn btn-success">Create your first list</a>
        </div>
    <?php else: ?>
        <?php foreach ($lists as $list): ?>
            <div class="tab-pane fade <?= $loopIndex === 0 ? 'show active' : '' ?>" id="list-<?= $list['id'] ?>">
                <a href="<?= BASE_URL ?>shopping_list_builder.php?id=<?= $list['id'] ?>" class="btn btn-primary">Edit This List →</a>
                <a href="<?= BASE_URL ?>orders/order.php?list_id=<?= $list['id'] ?>" class="btn btn-success">Turn into Order</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<a href="<?= BASE_URL ?>shopping_list_builder.php" class="btn btn-success mt-4">+ Create New Shopping List</a>

<?php require_once 'includes/footer.php'; ?>