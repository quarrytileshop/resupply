<?php
// admin_delete_vendor.php – Delete a vendor (with confirmation) – 2026-05-12
require_once 'config.php';
session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin'] || !isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = (int)$_GET['id'];

if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id AND is_organization_admin = 1");
    $stmt->execute(['id' => $id]);
    header("Location: admin_dashboard.php?msg=deleted");
    exit;
} else {
    // Show confirmation
    $stmt = $pdo->prepare("SELECT first_name, last_name, email FROM users WHERE id = :id AND is_organization_admin = 1");
    $stmt->execute(['id' => $id]);
    $vendor = $stmt->fetch();
    if (!$vendor) {
        header("Location: admin_dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Delete - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card">
            <div class="card-body text-center p-5">
                <h2 class="text-danger">Delete Vendor?</h2>
                <p>Are you sure you want to delete <strong><?= htmlspecialchars($vendor['first_name'] . ' ' . $vendor['last_name']) ?></strong> (<?= htmlspecialchars($vendor['email']) ?>)?</p>
                <p class="text-muted">This action cannot be undone and will remove the vendor and their associated data.</p>
                <a href="admin_delete_vendor.php?id=<?= $id ?>&confirm=yes" class="btn btn-danger btn-lg">Yes, Delete Vendor</a>
                <a href="admin_dashboard.php" class="btn btn-secondary btn-lg ms-3">Cancel</a>
            </div>
        </div>
    </div>
</body>
</html>
