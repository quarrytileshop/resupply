<?php
// admin_users.php – Modified 2026-05-08 – Lines: 220
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

// Fetch all users
$stmt = $pdo->query("SELECT u.*, o.name as organization_name 
                     FROM users u 
                     LEFT JOIN organizations o ON u.organization_id = o.id 
                     ORDER BY u.approval_status, u.first_name");
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_dashboard.php">Resupply Rocket Admin</a>
            <div class="navbar-nav">
                <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                <a class="nav-link" href="admin_organizations.php">Organizations</a>
                <a class="nav-link active" href="admin_users.php">Users</a>
                <a class="nav-link" href="admin_catalog.php">Catalog</a>
                <a class="nav-link" href="admin_orders.php">Orders</a>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Manage Users</h1>
        <p class="text-muted">Total users: <?= count($users) ?></p>

        <a href="#" class="btn btn-success mb-3">+ Invite New User</a>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Organization</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['organization_name'] ?? '—') ?></td>
                        <td>
                            <span class="badge bg-<?= $user['approval_status'] === 'approved' ? 'success' : 'warning' ?>">
                                <?= ucfirst($user['approval_status']) ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-sm btn-warning">Reset PW</button>
                            <?php if ($user['suspended']): ?>
                                <button class="btn btn-sm btn-success">Unsuspend</button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-danger">Suspend</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
