<?php
// shopping_list_builder.php – Updated with vendor_id isolation – 2026-05-11
$page_title = "Shopping List Builder - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}

$vendor_id = $_SESSION['vendor_id'] ?? 0;

// Fetch ONLY this vendor's customer organizations
$stmt = $pdo->prepare("SELECT id, name FROM organizations WHERE approval_status = 'approved' AND vendor_id = :vendor_id ORDER BY name");
$stmt->execute(['vendor_id' => $vendor_id]);
$customer_orgs = $stmt->fetchAll();

// Fetch catalog items (already scoped by vendor_id in DB)
$stmt = $pdo->prepare("SELECT id, item_name, description, price FROM catalog_items WHERE vendor_id = :vendor_id OR vendor_id IS NULL ORDER BY item_name");
$stmt->execute(['vendor_id' => $vendor_id]);
$catalog_items = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">Shopping List Builder</h1>
    <p class="text-muted">Create shopping lists for **your** customers only.</p>

    <div class="card">
        <div class="card-body">
            <form id="builderForm">
                <div class="mb-4">
                    <label class="form-label">Customer Organization</label>
                    <select id="orgSelect" class="form-select" required>
                        <option value="">Select an organization...</option>
                        <?php foreach ($customer_orgs as $org): ?>
                            <option value="<?= $org['id'] ?>"><?= htmlspecialchars($org['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">List Name</label>
                    <input type="text" id="listName" class="form-control" placeholder="e.g. Monthly Restock - May 2026" required>
                </div>

                <h5 class="mb-3">Available Catalog Items</h5>
                <div class="row g-3" id="catalogGrid">
                    <?php foreach ($catalog_items as $item): ?>
                    <div class="col-md-4">
                        <div class="card h-100 item-card" onclick="toggleItem(this, <?= $item['id'] ?>)">
                            <div class="card-body">
                                <h6><?= htmlspecialchars($item['item_name']) ?></h6>
                                <p class="text-muted small"><?= htmlspecialchars($item['description'] ?? '') ?></p>
                                <strong>$<?= number_format($item['price'], 2) ?></strong>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <button type="button" onclick="saveShoppingList()" class="btn btn-accent send-it-btn w-100 mt-4">
                    <img src="icons/logo-192.png" alt="Rocket" class="logo-img"> 
                    SAVE SHOPPING LIST
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Same JS as before (unchanged)
let selectedItems = [];
function toggleItem(el, itemId) { /* ... same as previous version ... */ }
function saveShoppingList() { /* ... same AJAX call ... */ }
</script>

<?php require_once 'footer.php'; ?>
