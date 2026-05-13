<?php
// vendor_shopping_lists.php – Full expanded version with vendor_id isolation – 2026-05-11
$page_title = "My Shopping Lists - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}

$vendor_id = $_SESSION['vendor_id'] ?? 0;

// Fetch only this vendor's shopping lists
$stmt = $pdo->prepare("SELECT sl.*, o.name as org_name, u.first_name 
                       FROM shopping_lists sl 
                       LEFT JOIN organizations o ON sl.organization_id = o.id 
                       LEFT JOIN users u ON sl.created_by = u.id 
                       WHERE sl.vendor_id = :vendor_id 
                       ORDER BY sl.created_at DESC");
$stmt->execute(['vendor_id' => $vendor_id]);
$lists = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">My Shopping Lists</h1>
    <p class="text-muted">All lists you’ve built for your customers.</p>

    <?php if (empty($lists)): ?>
        <div class="alert alert-info">No shopping lists yet. Go to the builder to create your first one.</div>
    <?php else: ?>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>List Name</th>
                                <th>Organization</th>
                                <th>Created By</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lists as $list): ?>
                            <tr>
                                <td><?= htmlspecialchars($list['name']) ?></td>
                                <td><?= htmlspecialchars($list['org_name']) ?></td>
                                <td><?= htmlspecialchars($list['first_name']) ?></td>
                                <td><?= date('M j, Y', strtotime($list['created_at'])) ?></td>
                                <td>
                                    <a href="shopping_list_builder.php?edit=<?= $list['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-4">
        <a href="vendor_dashboard.php" class="btn btn-secondary">← Back to Vendor Dashboard</a>
        <a href="shopping_list_builder.php" class="btn btn-primary ms-2">+ New Shopping List</a>
    </div>
</div>

<?php require_once 'footer.php'; ?>
