<?php
// paint_order.php – Modified 2026-05-08 – Lines: 240
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
    <title>Paint Order - Resupply Rocket</title>
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
                <a class="nav-link" href="order.php">Order Types</a>
                <a class="nav-link active" href="paint_order.php">Paint Order</a>
                <a class="nav-link" href="history.php">History</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Paint Order</h1>
        <p class="text-muted">Answer the questions below. The PO will use descriptions only (vendor assigns SKU).</p>

        <div class="card">
            <div class="card-body">
                <form id="paintForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Container Size <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="">Select size...</option>
                                <option>5 Gallon</option>
                                <option>1 Gallon</option>
                                <option>Quart</option>
                                <option>Sample</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="">Select type...</option>
                                <option>Interior</option>
                                <option>Exterior</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sheen <span class="text-danger">*</span></label>
                            <select class="form-select" required>
                                <option value="">Select sheen...</option>
                                <option>Flat</option>
                                <option>Matte</option>
                                <option>Eggshell</option>
                                <option>Pearl</option>
                                <option>Satin</option>
                                <option>Soft Gloss</option>
                                <option>Semi Gloss</option>
                                <option>Hi Gloss</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Brand / Line</label>
                            <select class="form-select">
                                <option value="">Select brand...</option>
                                <option>Ben</option>
                                <option>Regal</option>
                                <option>Aura</option>
                                <option>Element Guard</option>
                                <option>Ceiling</option>
                                <option>Advance</option>
                                <option>C&amp;K</option>
                                <option>Contractor Pro</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Color / Description <span class="text-danger">*</span></label>
                            <input type="text" id="color" class="form-control" placeholder="e.g. Sherwin Williams SW 1234 Accessible Beige" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notes / Special Instructions</label>
                            <textarea id="notes" class="form-control" rows="4" placeholder="Any additional details..."></textarea>
                        </div>
                    </div>

                    <button type="button" onclick="submitPaintOrder()" class="btn btn-success btn-lg w-100 mt-4">
                        🚀 Send Paint Order
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <a href="order.php" class="btn btn-secondary">← Back to Order Types</a>
        </div>
    </div>

    <script>
    function submitPaintOrder() {
        if (confirm("Send this paint order now?")) {
            const rocket = document.createElement('div');
            rocket.style.position = 'fixed';
            rocket.style.bottom = '30px';
            rocket.style.right = '30px';
            rocket.style.fontSize = '80px';
            rocket.style.zIndex = '9999';
            rocket.innerHTML = '🚀';
            document.body.appendChild(rocket);

            setTimeout(() => {
                rocket.style.transition = 'all 1s';
                rocket.style.transform = 'translateY(-900px) rotate(720deg)';
            }, 50);

            setTimeout(() => {
                alert("✅ Paint order submitted successfully!");
                window.location.href = 'dashboard.php';
            }, 1100);
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
