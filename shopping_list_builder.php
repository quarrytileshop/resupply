<?php
// shopping_list_builder.php – Professional shopping list builder for vendors – 2026-05-11
$page_title = "Shopping List Builder - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}

$organization_id = $_SESSION['organization_id'] ?? 0;

// Fetch all approved customer organizations (for vendor to choose from)
$stmt = $pdo->prepare("SELECT id, name FROM organizations WHERE approval_status = 'approved' ORDER BY name");
$stmt->execute();
$customer_orgs = $stmt->fetchAll();

// Fetch catalog items (scoped by vendor if needed – currently all)
$stmt = $pdo->query("SELECT id, item_name, description, price FROM catalog_items ORDER BY item_name");
$catalog_items = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">Shopping List Builder</h1>
    <p class="text-muted">Create or edit shopping lists for any of your customer organizations.</p>

    <div class="card">
        <div class="card-body">
            <div class="mb-4">
                <label class="form-label">Customer Organization</label>
                <select id="orgSelect" class="form-select">
                    <option value="">Select an organization...</option>
                    <?php foreach ($customer_orgs as $org): ?>
                        <option value="<?= $org['id'] ?>"><?= htmlspecialchars($org['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">List Name</label>
                <input type="text" id="listName" class="form-control" placeholder="e.g. Monthly Restock - May 2026">
            </div>

            <h5>Available Catalog Items</h5>
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

            <button onclick="saveShoppingList()" class="btn btn-accent send-it-btn w-100 mt-4">
                <img src="icons/logo-192.png" alt="Rocket" class="logo-img"> 
                SAVE SHOPPING LIST
            </button>
        </div>
    </div>
</div>

<script>
let selectedItems = [];

function toggleItem(el, itemId) {
    if (selectedItems.includes(itemId)) {
        selectedItems = selectedItems.filter(id => id !== itemId);
        el.classList.remove('border-primary');
    } else {
        selectedItems.push(itemId);
        el.classList.add('border-primary');
    }
}

function saveShoppingList() {
    const orgId = document.getElementById('orgSelect').value;
    const listName = document.getElementById('listName').value.trim();

    if (!orgId || !listName) {
        alert("Please select an organization and enter a list name.");
        return;
    }

    if (selectedItems.length === 0) {
        alert("Please select at least one catalog item.");
        return;
    }

    // In a real app this would be an AJAX call to a save endpoint
    // For now we simulate success
    alert(`✅ Shopping list "${listName}" saved for organization #${orgId} with ${selectedItems.length} items!`);
    window.location.href = 'vendor_dashboard.php';
}
</script>

<?php require_once 'footer.php'; ?>
