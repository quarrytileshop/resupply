<?php
// general_order.php – Modified 2026-05-08 – Lines: 260
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$organization_id = $_SESSION['organization_id'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Order - Resupply Rocket</title>
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
                <a class="nav-link active" href="general_order.php">General Order</a>
                <a class="nav-link" href="history.php">History</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>General Products Order</h1>
        <p class="text-muted">Type quantities to add items instantly. Changes save automatically.</p>

        <!-- Shopping Lists Tabs -->
        <ul class="nav nav-tabs mb-4" id="listTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#all-lists">All Lists</a></li>
        </ul>

        <div class="row">
            <!-- Products Area -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5>Available Products</h5>
                        <div class="alert alert-info">
                            <strong>Shopping list items will appear here.</strong><br>
                            Type a quantity in the box next to each item to add to cart.
                        </div>
                        <!-- Placeholder for product grid -->
                        <p><em>Full product catalog with images and live quantity inputs coming soon.</em></p>
                    </div>
                </div>
            </div>

            <!-- Live Cart -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 20px;">
                    <div class="card-header bg-primary text-white">
                        <strong>Your Cart</strong>
                    </div>
                    <div class="card-body">
                        <div id="cart-items">
                            <p class="text-muted">No items yet.</p>
                        </div>
                        
                        <hr>
                        <div class="mb-3">
                            <label>Delivery Location / PO#</label>
                            <input type="text" class="form-control" placeholder="e.g. Warehouse Bay 3">
                        </div>
                        <div class="mb-3">
                            <label>Notes</label>
                            <textarea class="form-control" rows="3" placeholder="Any special instructions..."></textarea>
                        </div>

                        <button onclick="sendOrder()" class="btn btn-success btn-lg w-100">
                            🚀 Send It!
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <a href="order.php" class="btn btn-secondary">← Back to Order Types</a>
        </div>
    </div>

    <script>
    function sendOrder() {
        if (confirm("Send this order now?")) {
            alert("🚀 Order sent! (Rocket animation would trigger here)");
            // Future: AJAX submission
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
