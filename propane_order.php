<?php
// propane_order.php – Modified 2026-05-08 – Lines: 180
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
    <title>Propane Order - Resupply Rocket</title>
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
                <a class="nav-link active" href="propane_order.php">Propane</a>
                <a class="nav-link" href="history.php">History</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Propane Order</h1>
        <p class="text-muted">Quick form for tank exchanges and new fills. No cart needed.</p>

        <div class="card">
            <div class="card-body">
                <form id="propaneForm">
                    <h5 class="mb-3">Exchanges</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">20 lb Tanks Exchanged</label>
                            <input type="number" id="exchange_20" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">30 lb Tanks Exchanged</label>
                            <input type="number" id="exchange_30" class="form-control" value="0" min="0">
                        </div>
                    </div>

                    <h5 class="mb-3">New Tanks</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">20 lb New Tanks</label>
                            <input type="number" id="new_20" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">30 lb New Tanks</label>
                            <input type="number" id="new_30" class="form-control" value="0" min="0">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Notes / Special Instructions</label>
                        <textarea id="notes" class="form-control" rows="4" placeholder="Delivery instructions, preferred time, etc."></textarea>
                    </div>

                    <button type="button" onclick="submitPropaneOrder()" class="btn btn-success btn-lg w-100">
                        🚀 Send Propane Order
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-4">
            <a href="order.php" class="btn btn-secondary">← Back to Order Types</a>
        </div>
    </div>

    <script>
    function submitPropaneOrder() {
        if (confirm("Send this propane order now?")) {
            // Rocket animation
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
                alert("✅ Propane order submitted successfully!");
                window.location.href = 'dashboard.php';
            }, 1100);
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
