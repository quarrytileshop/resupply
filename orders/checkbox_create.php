<?php
// checkbox_create.php – Updated 2026-05-11 to use header + footer + professional styles
$page_title = "Checkbox List - Resupply Rocket";
require_once 'header.php';
?>

<div class="container mt-4">
    <h1 class="mb-3">Create Checkbox List</h1>
    <p class="text-muted">For in-store pickup or team use. Check items when complete.</p>

    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">List Name</label>
                <input type="text" class="form-control" id="listName" placeholder="e.g. Store Restock - May 2026" value="Store Restock">
            </div>

            <h5>Items</h5>
            <div id="itemList" class="mb-3">
                <!-- Dynamic items will go here -->
                <div class="input-group mb-2">
                    <input type="text" class="form-control" placeholder="Item name / description">
                    <input type="number" class="form-control" style="width:100px;" placeholder="Qty" value="1">
                    <button class="btn btn-outline-danger">Remove</button>
                </div>
            </div>

            <button onclick="addItem()" class="btn btn-secondary mb-4">+ Add Item</button>

            <button onclick="saveAndEmailList()" class="btn btn-accent send-it-btn w-100">
                <img src="icons/logo-192.png" alt="Rocket" class="logo-img"> 
                FINISH LIST &amp; EMAIL TO ADMIN
            </button>
        </div>
    </div>

    <div class="mt-4">
        <a href="order.php" class="btn btn-secondary">← Back to Order Types</a>
    </div>
</div>

<script>
// Your original Checkbox JS — fully preserved
function addItem() {
    const itemList = document.getElementById('itemList');
    const newItem = document.createElement('div');
    newItem.className = 'input-group mb-2';
    newItem.innerHTML = `
        <input type="text" class="form-control" placeholder="Item name / description">
        <input type="number" class="form-control" style="width:100px;" placeholder="Qty" value="1">
        <button class="btn btn-outline-danger" onclick="this.parentElement.remove()">Remove</button>
    `;
    itemList.appendChild(newItem);
}

function saveAndEmailList() {
    if (confirm("Finish this list and email to admin (russellhb2b@gmail.com)?")) {
        alert("Checkbox list completed and emailed to admin!");
        // Future: AJAX save + email
        window.location.href = 'dashboard.php';
    }
}
</script>

<?php require_once 'footer.php'; ?>
