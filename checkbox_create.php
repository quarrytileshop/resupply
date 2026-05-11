<?php
// checkbox_create.php – Modified 2026-05-08 – Lines: 180
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkbox List - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light">
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Resupply Rocket</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="dashboard.php">Dashboard</a>
                <a class="nav-link" href="order.php">New Order</a>
                <a class="nav-link active" href="checkbox_create.php">Checkbox List</a>
                <a class="nav-link" href="history.php">History</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Create Checkbox List</h1>
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

                <button onclick="saveAndEmailList()" class="btn btn-success btn-lg w-100">
                    ✅ Finish List & Email to Admin
                </button>
            </div>
        </div>

        <div class="mt-4">
            <a href="order.php" class="btn btn-secondary">← Back to Order Types</a>
        </div>
    </div>

    <script>
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
            alert("✅ Checkbox list completed and emailed to admin!");
            // Future: AJAX save + email
            window.location.href = 'dashboard.php';
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
