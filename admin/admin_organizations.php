<?php
// admin_organizations.php – Modified 2026-05-08 – Lines: 480
require_once 'config.php';
require_once 'email_functions.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

// Handle approval
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];
    $org_id = intval($_POST['organization_id'] ?? 0);
    $account_number = trim($_POST['account_number'] ?? '');

    try {
        if ($action === 'approve' && $org_id > 0 && $account_number) {
            $stmt = $pdo->prepare("UPDATE organizations SET approval_status = 'approved', account_number = :acct WHERE id = :id");
            $stmt->execute(['acct' => $account_number, 'id' => $org_id]);

            $stmt = $pdo->prepare("UPDATE users SET approval_status = 'approved' WHERE organization_id = :id");
            $stmt->execute(['id' => $org_id]);

            echo json_encode(['success' => true, 'message' => 'Organization approved successfully!']);
            exit;
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

// Fetch data for tabs
$pending  = $pdo->query("SELECT * FROM organizations WHERE approval_status = 'pending' ORDER BY id DESC")->fetchAll();
$approved = $pdo->query("SELECT * FROM organizations WHERE approval_status = 'approved' ORDER BY id DESC")->fetchAll();
$all      = $pdo->query("SELECT * FROM organizations ORDER BY approval_status, id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Organizations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light">
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Resupply Rocket Admin</a>
            <div class="navbar-nav">
                <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                <a class="nav-link active" href="admin_organizations.php">Organizations</a>
                <a class="nav-link" href="admin_users.php">Users</a>
                <a class="nav-link" href="admin_catalog.php">Catalog</a>
                <a class="nav-link" href="admin_orders.php">Orders</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Manage Organizations</h1>

        <ul class="nav nav-tabs mb-4">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#pending">Pending</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#approved">Approved</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#all">All Organizations</a></li>
        </ul>

        <div class="tab-content">
            <!-- Pending Tab -->
            <div class="tab-pane fade show active" id="pending">
                <?php if (empty($pending)): ?>
                    <div class="alert alert-info">No pending organizations.</div>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead><tr><th>Name</th><th>Contact</th><th>Type</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php foreach ($pending as $org): ?>
                            <tr>
                                <td><?= htmlspecialchars($org['name']) ?></td>
                                <td><?= htmlspecialchars($org['contact_email']) ?></td>
                                <td><?= htmlspecialchars($org['type']) ?></td>
                                <td><button onclick="approveOrg(<?= $org['id'] ?>)" class="btn btn-success btn-sm">Approve</button></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Approved Tab -->
            <div class="tab-pane fade" id="approved">
                <?php if (empty($approved)): ?>
                    <div class="alert alert-info">No approved organizations yet.</div>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead><tr><th>Name</th><th>Contact</th><th>Account #</th><th>Type</th></tr></thead>
                        <tbody>
                            <?php foreach ($approved as $org): ?>
                            <tr>
                                <td><?= htmlspecialchars($org['name']) ?></td>
                                <td><?= htmlspecialchars($org['contact_email']) ?></td>
                                <td><?= htmlspecialchars($org['account_number'] ?? '—') ?></td>
                                <td><?= htmlspecialchars($org['type']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- All Tab -->
            <div class="tab-pane fade" id="all">
                <table class="table table-striped">
                    <thead><tr><th>Name</th><th>Status</th><th>Account #</th><th>Type</th></tr></thead>
                    <tbody>
                        <?php foreach ($all as $org): ?>
                        <tr>
                            <td><?= htmlspecialchars($org['name']) ?></td>
                            <td><span class="badge bg-<?= $org['approval_status'] === 'approved' ? 'success' : ($org['approval_status'] === 'pending' ? 'warning' : 'secondary') ?>"><?= ucfirst($org['approval_status']) ?></span></td>
                            <td><?= htmlspecialchars($org['account_number'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($org['type']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    function approveOrg(id) {
        const acct = prompt("Enter Account Number:");
        if (!acct) return;
        if (confirm("Approve with account number " + acct + "?")) {
            $.post(window.location.href, {action: 'approve', organization_id: id, account_number: acct}, function(res) {
                alert(res.message || "Approved!");
                location.reload();
            }, 'json');
        }
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
