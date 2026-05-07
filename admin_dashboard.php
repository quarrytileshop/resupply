<?php
// admin_dashboard.php – Modified March 11, 2025 20:15 PDT – Lines: 152
require_once 'config.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing admin_dashboard.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
ini_set('session.gc_maxlifetime', 14400);
ini_set('session.cookie_lifetime', 14400);
ini_set('session.cache_expire', 14400);
ini_set('session.use_only_cookies', 1);
session_set_cookie_params(14400, '/', null, false, true);
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

try {
    $stmt = $pdo->prepare("SELECT email, phone_number FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();
    if (!$user) {
        die("User not found.");
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// Handle reset organization to pending (for testing)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_organization'])) {
    $organization_id = intval($_POST['organization_id']);
    try {
        $stmt = $pdo->prepare("UPDATE organizations SET approval_status = 'pending', organization_account_number = '' WHERE id = :id");
        $stmt->execute(['id' => $organization_id]);
        header("Location: admin_dashboard.php?message=Organization reset to pending.");
        exit;
    } catch (PDOException $e) {
        error_log("Reset organization error: " . $e->getMessage());
        header("Location: admin_dashboard.php?error=Database error.");
        exit;
    }
}

// Fetch organizations for reset dropdown
$stmt = $pdo->query("SELECT id, name FROM organizations ORDER BY name ASC");
$organizations = $stmt->fetchAll();
$message = $_GET['message'] ?? '';
$error = $_GET['error'] ?? '';

// Handle impersonation search
$search_query = '';
$search_results = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_users'])) {
    $search_query = filter_input(INPUT_POST, 'search_query', FILTER_SANITIZE_STRING);
    $stmt = $pdo->prepare("SELECT u.id, u.username, u.email, u.organization_id, o.name AS organization_name 
                           FROM users u LEFT JOIN organizations o ON u.organization_id = o.id 
                           WHERE u.username LIKE :query OR u.email LIKE :query OR o.name LIKE :query");
    $stmt->execute(['query' => "%$search_query%"]);
    $search_results = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Resupply Rocket Logo" class="logo">
        <h1>Admin Dashboard</h1>
        <p>Logged in as: Admin <?php echo htmlspecialchars($username); ?></p>
        <?php if ($message) echo "<p class='success'>$message</p>"; ?>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <div class="card mb-3">
            <div class="card-body">
                <h3>Admin Tools</h3>
                <a href="admin_pre_register.php" class="btn btn-primary mb-2">Pre-Register User</a>
                <a href="admin_organizations.php" class="btn btn-secondary mb-2">Manage Organizations</a>
                <a href="admin_users.php" class="btn btn-secondary mb-2">Manage Users</a>
                <a href="admin_catalog.php" class="btn btn-secondary mb-2">Manage Catalog</a>
                <a href="admin_shopping_lists.php" class="btn btn-secondary mb-2">Manage Shopping Lists</a>
                <a href="admin_orders.php" class="btn btn-secondary mb-2 disabled">View Orders (Coming Soon)</a>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h3>Impersonate User</h3>
                <form method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="search_query" class="form-control" placeholder="Search by username, email, or organization name" value="<?php echo htmlspecialchars($search_query); ?>" required>
                        <button type="submit" name="search_users" class="btn btn-primary">Search</button>
                    </div>
                </form>
                <?php if (!empty($search_results)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Organization</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($search_results as $result): ?>
                                    <tr>
                                        <td><?php echo $result['id']; ?></td>
                                        <td><?php echo htmlspecialchars($result['username']); ?></td>
                                        <td><?php echo htmlspecialchars($result['email']); ?></td>
                                        <td><?php echo htmlspecialchars($result['organization_name']); ?></td>
                                        <td>
                                            <form method="post" action="admin_impersonate.php">
                                                <input type="hidden" name="target_user_id" value="<?php echo $result['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-info">Impersonate</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h3>Quick Actions</h3>
                <a href="order.php" class="btn btn-primary mb-2">Test Propane Order</a>
                <a href="general_order.php" class="btn btn-primary mb-2">Test General Order</a>
                <a href="forgot_password.php" class="btn btn-secondary mb-2">Reset Password</a>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <h3>Test Registration Reset</h3>
                <form method="post">
                    <label>Select Organization to Reset to Pending:</label>
                    <select name="organization_id" class="form-control mb-2" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($organizations as $organization): ?>
                            <option value="<?php echo $organization['id']; ?>"><?php echo htmlspecialchars($organization['name']); ?> (ID: <?php echo $organization['id']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="reset_organization" class="btn btn-info w-100">Reset to Pending</button>
                </form>
            </div>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <a href="admin_shopping_lists.php" class="btn btn-outline-primary me-2">Shopping Lists</a>
            <a href="admin_organization_catalog.php" class="btn btn-outline-primary me-2">Organization Catalog</a>
            <a href="admin_dashboard.php" class="btn btn-outline-secondary">Dashboard</a>
        </div>
        <a href="logout.php">Logout</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
