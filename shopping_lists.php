<?php
// shopping_lists.php – Modified 2026-05-08 – Lines: 160
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$organization_id = $_SESSION['organization_id'] ?? 0;

// Fetch shopping lists for this organization
$stmt = $pdo->prepare("SELECT * FROM shopping_lists 
                       WHERE organization_id = :org_id OR is_global = 1 
                       ORDER BY name");
$stmt->execute(['org_id' => $organization_id]);
$lists = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Lists - Resupply Rocket</title>
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
                <a class="nav-link active" href="shopping_lists.php">Shopping Lists</a>
                <a class="nav-link" href="order.php">New Order</a>
                <a class="nav-link" href="history.php">History</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Your Shopping Lists</h1>
        <p class="text-muted">Select a list to start ordering</p>

        <?php if (empty($lists)): ?>
            <div class="alert alert-info">No shopping lists available yet.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($lists as $list): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($list['name']) ?></h5>
                            <p class="card-text text-muted"><?= htmlspecialchars($list['description'] ?? 'No description') ?></p>
                            <a href="general_order.php?list_id=<?= $list['id'] ?>" class="btn btn-primary w-100">
                                Use This List
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
