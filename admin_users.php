<?php
// admin_users.php – Modified 2025-03-11 14:15 PDT – Lines: 328
require_once 'config.php';
require_once 'email_functions.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing admin_users.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

// Handle AJAX actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];
    $user_id = intval($_POST['user_id'] ?? 0);
    try {
        if ($action === 'approve') {
            $stmt = $pdo->prepare("SELECT password_hash, email FROM users WHERE id = :id AND approval_status = 'pending'");
            $stmt->execute(['id' => $user_id]);
            $user = $stmt->fetch();
            if ($user) {
                $stmt = $pdo->prepare("UPDATE users SET approval_status = 'approved' WHERE id = :id");
                $stmt->execute(['id' => $user_id]);
                $subject = "Account Approved";
                $link_sent = false;
                if (empty($user['password_hash'])) {
                    $token = bin2hex(random_bytes(32));
                    $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
                    $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, expires_at) VALUES (:user_id, :token, :expires)");
                    $stmt->execute(['user_id' => $user_id, 'token' => $token, 'expires' => $expires]);
                    $link = "https://test.resupplyrocket.com/set_password.php?token=$token";
                    $html_body = '<html><body style="font-family: Arial; color: #333;"><div style="max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd;"><img src="https://' . $_SERVER['HTTP_HOST'] . '/icons/logo-192.png" alt="Logo" style="max-width: 150px;"><h2>Account Approved</h2><p>Please set your password using the link below, then log in.</p><a href="' . $link . '">Set Password</a><p>Link expires in 1 hour.</p></div></body></html>';
                    $plain_body = "Account approved. Set password: $link (expires in 1 hour). Then log in.";
                    $link_sent = true;
                } else {
                    $html_body = '<html><body style="font-family: Arial; color: #333;"><div style="max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd;"><img src="https://' . $_SERVER['HTTP_HOST'] . '/icons/logo-192.png" alt="Logo" style="max-width: 150px;"><h2>Account Approved</h2><p>Your account has been approved. You can now log in.</p></div></body></html>';
                    $plain_body = "Your account has been approved. You can now log in.";
                }
                if (send_email($user['email'], $subject, $html_body, $plain_body)) {
                    echo json_encode(['success' => true, 'message' => 'User approved' . ($link_sent ? ' and password link sent.' : '.')]);
                } else {
                    echo json_encode(['success' => true, 'message' => 'User approved, but email failed.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Not pending or not found.']);
            }
        } elseif ($action === 'suspend') {
            $stmt = $pdo->prepare("UPDATE users SET suspended = 1 WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            echo json_encode(['success' => true, 'message' => 'Suspended.']);
        } elseif ($action === 'unsuspend') {
            $stmt = $pdo->prepare("UPDATE users SET suspended = 0 WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            echo json_encode(['success' => true, 'message' => 'Unsuspended.']);
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            echo json_encode(['success' => true, 'message' => 'Deleted.']);
        } elseif ($action === 'edit') {
            $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
            $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
            $is_propane = intval($_POST['is_propane'] ?? 0);
            $stmt = $pdo->prepare("UPDATE users SET first_name = :fn, last_name = :ln, username = :un, email = :email, phone_number = :phone, is_propane = :propane WHERE id = :id");
            $stmt->execute([
                'fn' => $first_name,
                'ln' => $last_name,
                'un' => $first_name . ' ' . $last_name,
                'email' => $email,
                'phone' => $phone,
                'propane' => $is_propane,
                'id' => $user_id
            ]);
            echo json_encode(['success' => true, 'message' => 'Updated.']);
        } elseif ($action === 'fetch_details') {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            $user = $stmt->fetch();
            echo json_encode(['success' => true, 'user' => $user]);
        } elseif ($action === 'toggle_organization_admin') {
            $is_admin = intval($_POST['is_admin']);
            $stmt = $pdo->prepare("UPDATE users SET is_organization_admin = :is_admin WHERE id = :id");
            $stmt->execute(['is_admin' => $is_admin, 'id' => $user_id]);
            echo json_encode(['success' => true, 'message' => 'Organization admin status updated.']);
        }
    } catch (PDOException $e) {
        error_log("User action error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// Fetch organizations for dropdown
$stmt = $pdo->query("SELECT id, name FROM organizations ORDER BY name");
$organizations = $stmt->fetchAll();

// Fetch users grouped by organization
$stmt = $pdo->query("SELECT u.*, o.name AS organization_name FROM users u LEFT JOIN organizations o ON u.organization_id = o.id ORDER BY o.name, u.username");
$users = $stmt->fetchAll();
$grouped_users = [];
foreach ($users as $user) {
    $organization = $user['organization_name'] ?: 'No Organization';
    $grouped_users[$organization][] = $user;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Manage Users</h1>
        <div id="responseMessage"></div>
        <!-- Add New User Form -->
        <div class="card mb-3">
            <div class="card-body">
                <h3>Add New User</h3>
                <form id="addUserForm" method="post">
                    <label>Organization:</label>
                    <select name="organization_id" class="form-control" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($organizations as $organization): ?>
                            <option value="<?php echo $organization['id']; ?>"><?php echo htmlspecialchars($organization['name']); ?></option>
                        <?php endforeach; ?>
                    </select><br>
                    <label>First Name:</label><input type="text" name="first_name" class="form-control" required><br>
                    <label>Last Name:</label><input type="text" name="last_name" class="form-control" required><br>
                    <label>Email:</label><input type="email" name="email" class="form-control" required><br>
                    <label>Phone:</label><input type="tel" name="phone" class="form-control"><br>
                    <label><input type="checkbox" name="is_propane" value="1"> Propane-Focused</label><br>
                    <button type="submit" class="btn btn-primary w-100">Add User</button>
                </form>
            </div>
        </div>
        <!-- Users List -->
        <div class="accordion" id="usersAccordion">
            <?php foreach ($grouped_users as $organization => $organization_users): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#organization<?php echo md5($organization); ?>">
                            <?php echo htmlspecialchars($organization); ?> (<?php echo count($organization_users); ?> users)
                        </button>
                    </h2>
                    <div id="organization<?php echo md5($organization); ?>" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Organization Admin</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($organization_users as $user): ?>
                                            <tr data-user-id="<?php echo $user['id']; ?>">
                                                <td><?php echo $user['id']; ?></td>
                                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                                <td><?php echo htmlspecialchars($user['phone_number']); ?></td>
                                                <td><?php echo htmlspecialchars($user['approval_status']) . ($user['suspended'] ? ' (Suspended)' : ''); ?></td>
                                                <td>
                                                    <input type="checkbox" class="organization-admin-toggle" data-user-id="<?php echo $user['id']; ?>" <?php echo $user['is_organization_admin'] ? 'checked' : ''; ?>>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary edit-user" data-bs-toggle="modal" data-bs-target="#editUserModal">Edit</button>
                                                    <?php if ($user['approval_status'] === 'pending'): ?>
                                                        <button class="btn btn-sm btn-success approve-user">Approve</button>
                                                    <?php endif; ?>
                                                    <button class="btn btn-sm btn-warning suspend-user">Suspend</button>
                                                    <button class="btn btn-sm btn-info unsuspend-user" style="display:none;">Unsuspend</button>
                                                    <button class="btn btn-sm btn-danger delete-user">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Edit Modal -->
        <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editUserForm">
                            <input type="hidden" name="user_id" id="edit_user_id">
                            <label>First Name:</label><input type="text" name="first_name" id="edit_first_name" class="form-control"><br>
                            <label>Last Name:</label><input type="text" name="last_name" id="edit_last_name" class="form-control"><br>
                            <label>Email:</label><input type="email" name="email" id="edit_email" class="form-control"><br>
                            <label>Phone:</label><input type="tel" name="phone" id="edit_phone" class="form-control"><br>
                            <label><input type="checkbox" name="is_propane" id="edit_is_propane"> Propane-Focused</label><br>
                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <a href="admin_dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Add User
            $('#addUserForm').submit(function(e) {
                e.preventDefault();
                $.post('admin_pre_register.php', $(this).serialize() + '&action=pre_register', function(response) {
                    $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                    if (response.success) location.reload();
                }, 'json');
            });
            // Edit User Modal Populate
            $('.edit-user').click(function() {
                let userId = $(this).closest('tr').data('user-id');
                $.post('admin_users.php', { action: 'fetch_details', user_id: userId }, function(response) {
                    if (response.success) {
                        let user = response.user;
                        $('#edit_user_id').val(user.id);
                        $('#edit_first_name').val(user.first_name);
                        $('#edit_last_name').val(user.last_name);
                        $('#edit_email').val(user.email);
                        $('#edit_phone').val(user.phone_number);
                        $('#edit_is_propane').prop('checked', user.is_propane == 1);
                    }
                }, 'json');
            });
            $('#editUserForm').submit(function(e) {
                e.preventDefault();
                $.post('admin_users.php', $(this).serialize() + '&action=edit', function(response) {
                    $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                    if (response.success) {
                        $('#editUserModal').modal('hide');
                        location.reload();
                    }
                }, 'json');
            });
            // Approve
            $('.approve-user').click(function() {
                let userId = $(this).closest('tr').data('user-id');
                if (confirm('Approve user?')) {
                    $.post('admin_users.php', { action: 'approve', user_id: userId }, function(response) {
                        $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                        if (response.success) location.reload();
                        else alert(response.message);
                    }, 'json');
                }
            });
            // Suspend
            $('.suspend-user').click(function() {
                let userId = $(this).closest('tr').data('user-id');
                if (confirm('Suspend user?')) {
                    $.post('admin_users.php', { action: 'suspend', user_id: userId }, function(response) {
                        $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                        if (response.success) location.reload();
                        else alert(response.message);
                    }, 'json');
                }
            });
            // Unsuspend
            $('.unsuspend-user').click(function() {
                let userId = $(this).closest('tr').data('user-id');
                if (confirm('Unsuspend user?')) {
                    $.post('admin_users.php', { action: 'unsuspend', user_id: userId }, function(response) {
                        $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                        if (response.success) location.reload();
                        else alert(response.message);
                    }, 'json');
                }
            });
            // Delete
            $('.delete-user').click(function() {
                let userId = $(this).closest('tr').data('user-id');
                if (confirm('Delete user?')) {
                    $.post('admin_users.php', { action: 'delete', user_id: userId }, function(response) {
                        $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                        if (response.success) location.reload();
                        else alert(response.message);
                    }, 'json');
                }
            });
            // Toggle Organization Admin
            $(document).on('change', '.organization-admin-toggle', function() {
                let userId = $(this).data('user-id');
                let isAdmin = this.checked ? 1 : 0;
                $.post('admin_users.php', { action: 'toggle_organization_admin', user_id: userId, is_admin: isAdmin }, function(response) {
                    if (response.success) {
                        $('#responseMessage').html('<p class="success">' + response.message + '</p>');
                    } else {
                        alert(response.message);
                    }
                }, 'json');
            });
        });
    </script>
</body>
</html>
