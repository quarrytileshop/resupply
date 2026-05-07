<?php
// dashboard.php – Modified 2025-03-10 20:45 – Lines: 200
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT organization_id, is_propane, is_organization_admin FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
$organization_id = $user['organization_id'];
$is_propane = $user['is_propane'];
$is_organization_admin = $user['is_organization_admin'];

// Fetch last three orders
$stmt = $pdo->prepare("SELECT o.id, o.po_number, o.created_at, o.fulfillment_type AS type, u.username AS ordered_by FROM orders o JOIN users u ON o.user_id = u.id WHERE u.organization_id = :organization_id AND o.status = 'sent' ORDER BY o.created_at DESC LIMIT 3");
$stmt->execute(['organization_id' => $organization_id]);
$recent_orders = $stmt->fetchAll();

// Fetch messages
$stmt = $pdo->prepare("SELECT m.id, m.content, m.type FROM messages m LEFT JOIN user_messages um ON m.id = um.message_id AND um.user_id = :user WHERE (m.type = 'persistent' OR (m.type = 'dismissable' AND (um.dismissed IS NULL OR um.dismissed = 0)))");
$stmt->execute(['user' => $user_id]);
$messages = $stmt->fetchAll();

// Fetch checkbox lists
$stmt = $pdo->prepare("SELECT cl.id, cl.name, cl.created_at, cl.creator_id, u.username AS creator FROM checkbox_lists cl JOIN users u ON cl.creator_id = u.id WHERE cl.organization_id = :organization_id AND cl.archived = 0 ORDER BY cl.created_at DESC");
$stmt->execute(['organization_id' => $organization_id]);
$checkbox_lists = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Dashboard</h1>
        <?php foreach ($messages as $msg): ?>
            <div class="alert alert-info <?php echo $msg['type'] == 'dismissable' ? 'dismissable' : ''; ?>" data-msg-id="<?php echo $msg['id']; ?>">
                <?php echo htmlspecialchars($msg['content']); ?>
                <?php if ($msg['type'] == 'dismissable'): ?><button class="btn-close" data-bs-dismiss="alert"></button><?php endif; ?>
            </div>
        <?php endforeach; ?>
        <div class="row mb-3">
            <div class="col"><a href="<?php echo $is_propane ? 'order.php' : 'general_order.php'; ?>" class="btn btn-primary w-100">New General Order</a></div>
            <div class="col"><a href="paint_order.php" class="btn btn-primary w-100">New Paint Order</a></div>
            <div class="col"><a href="checkbox_create.php" class="btn btn-primary w-100">Create Checkbox List</a></div>
        </div>
        <?php if ($is_organization_admin): ?>
            <a href="invite_user.php" class="btn btn-secondary mb-3">Invite New User</a>
            <a href="organization_admin.php" class="btn btn-secondary mb-3">Manage Organization</a>
        <?php endif; ?>
        <h3>Recent Orders</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead><tr><th>PO#</th><th>Date</th><th>Type</th><th>Ordered By</th><th>View</th></tr></thead>
                <tbody>
                    <?php foreach ($recent_orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['po_number']); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></td>
                            <td><?php echo ucfirst($order['type']); ?></td>
                            <td><?php echo htmlspecialchars($order['ordered_by']); ?></td>
                            <td><a href="view_order.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-info">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <h3>Checkbox Lists</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead><tr><th>Name</th><th>Creator</th><th>Created</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($checkbox_lists as $list): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($list['name']); ?></td>
                            <td><?php echo htmlspecialchars($list['creator']); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($list['created_at'])); ?></td>
                            <td>
                                <a href="checkbox_view.php?id=<?php echo $list['id']; ?>" class="btn btn-sm btn-info">View</a>
                                <?php if ($list['creator_id'] == $user_id): ?>
                                    <a href="checkbox_create.php?id=<?php echo $list['id']; ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="history.php" class="btn btn-secondary">View History</a>
        <a href="edit_profile.php" class="btn btn-secondary">Edit Profile</a>
        <a href="reset_password.php" class="btn btn-secondary">Reset Password</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
    <script>
        $('.dismissable .btn-close').click(function() {
            let msgId = $(this).closest('.alert').data('msg-id');
            $.post('dismiss_message.php', {msg_id: msgId});
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
