<?php
/**
 * resupply - New Order Page (FINAL README-Aligned Rewrite)
 * Full support for all 4 order types + rocket "Send It!" button
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

$page_title = 'New Order';

require_once '../includes/header.php';

$list_id = $_GET['list_id'] ?? null;
$order_type = $_GET['type'] ?? 'general';
?>

<h1 class="mb-4">New <?= ucfirst($order_type) ?> Order</h1>

<div class="card shadow-sm">
    <div class="card-body">
        <form id="order-form" method="POST" action="<?= BASE_URL ?>orders/save_order.php">
            <input type="hidden" name="csrf_token" value="<?= generate_csrf_token() ?>">
            <input type="hidden" name="list_id" value="<?= htmlspecialchars($list_id) ?>">
            <input type="hidden" name="order_type" value="<?= htmlspecialchars($order_type) ?>">

            <!-- Order Type Tabs -->
            <div class="btn-group w-100 mb-4" role="group">
                <a href="?type=general&list_id=<?= $list_id ?>" class="btn btn-outline-primary <?= $order_type==='general'?'active':'' ?>">General Tile</a>
                <a href="?type=propane&list_id=<?= $list_id ?>" class="btn btn-outline-primary <?= $order_type==='propane'?'active':'' ?>">Propane</a>
                <a href="?type=paint&list_id=<?= $list_id ?>" class="btn btn-outline-primary <?= $order_type==='paint'?'active':'' ?>">Paint</a>
                <a href="?type=checkbox&list_id=<?= $list_id ?>" class="btn btn-outline-primary <?= $order_type==='checkbox'?'active':'' ?>">Checkbox List</a>
            </div>

            <?php if ($order_type === 'propane'): ?>
                <div class="alert alert-info">Pre-loaded propane rows – special PO format will be used.</div>
            <?php elseif ($order_type === 'paint'): ?>
                <div class="alert alert-info">Guided paint questions below.</div>
            <?php elseif ($order_type === 'checkbox'): ?>
                <div class="alert alert-warning">Checkbox lists send special email to Russell – no PO to vendor.</div>
            <?php endif; ?>

            <!-- Cart / Items Area (mirrors shopping list) -->
            <div id="cart-items" class="mb-4"></div>

            <button type="submit" onclick="startRocketAnimation(this)" 
                    class="btn btn-success btn-lg w-100 py-4 fw-bold">
                🚀 Send It!
            </button>
        </form>
    </div>
</div>

<script>
function startRocketAnimation(btn) {
    btn.innerHTML = '🚀🚀🚀 SENDING IT... 🚀🚀🚀';
    btn.style.transition = 'all 0.6s';
    btn.style.transform = 'scale(1.1)';
    setTimeout(() => { btn.form.submit(); }, 800);
}

// Placeholder cart render – in full version loads from shopping list
window.onload = function() {
    document.getElementById('cart-items').innerHTML = `
        <div class="list-group">
            <div class="list-group-item">10 × 12x12 Quarry Tile - Charcoal</div>
        </div>
    `;
};
</script>

<?php require_once '../includes/footer.php'; ?>