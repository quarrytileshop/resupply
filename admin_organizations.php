<?php
// admin_organizations.php – Modified 2025-03-11 13:45 PDT – Lines: 368
require_once 'config.php';
require_once 'email_functions.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing admin_organizations.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

// Handle AJAX actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];
    $organization_id = intval($_POST['organization_id'] ?? 0);
    try {
        if ($action === 'approve') {
            $organization_account_number = filter_input(INPUT_POST, 'organization_account_number', FILTER_SANITIZE_STRING);
            $stmt = $pdo->prepare("UPDATE organizations SET approval_status = 'approved', organization_account_number = :organization_account_number WHERE id = :id AND approval_status = 'pending'");
            $stmt->execute(['organization_account_number' => $organization_account_number, 'id' => $organization_id]);
            if ($stmt->rowCount() > 0) {
                // Approve associated users
                $stmt = $pdo->prepare("UPDATE users SET approval_status = 'approved' WHERE organization_id = :id AND approval_status = 'pending'");
                $stmt->execute(['id' => $organization_id]);
                // Fetch organization
                $stmt = $pdo->prepare("SELECT * FROM organizations WHERE id = :id");
                $stmt->execute(['id' => $organization_id]);
                $organization = $stmt->fetch();
                // Fetch initial user
                $stmt = $pdo->prepare("SELECT first_name, last_name, email, phone_number FROM users WHERE organization_id = :organization_id ORDER BY id ASC LIMIT 1");
                $stmt->execute(['organization_id' => $organization_id]);
                $initial_user = $stmt->fetch();
                $initial_user_name = $initial_user ? htmlspecialchars($initial_user['first_name'] . ' ' . $initial_user['last_name']) : 'N/A';
                $initial_user_email = $initial_user ? htmlspecialchars($initial_user['email']) : 'N/A';
                $initial_user_phone = $initial_user ? htmlspecialchars($initial_user['phone_number']) : 'N/A';
                // Parse authorized_people JSON for fact sheet
                $auth = json_decode($organization['organization_authorized_people'] ?? '[]', true);
                $auth_str = '';
                foreach ($auth as $person) {
                    $auth_str .= htmlspecialchars($person['name']) . ' (' . htmlspecialchars($person['email']) . '), ';
                }
                $auth_str = rtrim($auth_str, ', ') ?: 'N/A';
                // Credit card section (simplified – add real fields if you have them)
                $cc_section_html = '<tr><td><strong>Credit Card on File:</strong> No</td></tr>';
                $cc_section_plain = "\nCredit Card on File: No";
                // Fact sheet HTML
                $fact_sheet_html = '<html><body style="font-family: Arial; color: #333;"><div style="max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd;"><img src="https://' . $_SERVER['HTTP_HOST'] . '/icons/logo-192.png" alt="Logo" style="max-width: 150px;"><h2>New Organization Fact Sheet</h2><table style="width: 100%; border-collapse: collapse;"><tr><td><strong>Date:</strong> ' . date('Y-m-d H:i:s') . '</td></tr><tr><td><strong>Name:</strong> ' . htmlspecialchars($organization['name']) . '</td></tr><tr><td><strong>Physical Address:</strong> ' . htmlspecialchars($organization['address']) . '</td></tr><tr><td><strong>Billing Address:</strong> ' . htmlspecialchars($organization['mailing_address']) . '</td></tr><tr><td><strong>Contact Name:</strong> ' . htmlspecialchars($organization['contact_name']) . '</td></tr><tr><td><strong>Contact Email:</strong> ' . htmlspecialchars($organization['contact_email']) . '</td></tr><tr><td><strong>Account Number:</strong> ' . htmlspecialchars($organization_account_number) . '</td></tr><tr><td><strong>Type:</strong> ' . htmlspecialchars($organization['organization_type']) . '</td></tr><tr><td><strong>Resale Number:</strong> ' . htmlspecialchars($organization['organization_resale_number'] ?? 'N/A') . '</td></tr><tr><td><strong>Authorized People:</strong> ' . $auth_str . '</td></tr><tr><td><strong>Initial Applicant:</strong> ' . $initial_user_name . ' (' . $initial_user_email . ', ' . $initial_user_phone . ')</td></tr>' . $cc_section_html . '</table></div></body></html>';
                $fact_sheet_plain = "New Organization Fact Sheet\nDate: " . date('Y-m-d H:i:s') . "\nName: " . htmlspecialchars($organization['name']) . "\nPhysical: " . htmlspecialchars($organization['address']) . "\nBilling: " . htmlspecialchars($organization['mailing_address']) . "\nContact: " . htmlspecialchars($organization['contact_name']) . " (" . htmlspecialchars($organization['contact_email']) . ")\nAccount: " . htmlspecialchars($organization_account_number) . "\nType: " . htmlspecialchars($organization['organization_type']) . "\nResale: " . htmlspecialchars($organization['organization_resale_number'] ?? 'N/A') . "\nAuthorized: " . $auth_str . "\nInitial: " . $initial_user_name . " (" . $initial_user_email . ", " . $initial_user_phone . ")" . $cc_section_plain;
                if (send_email('russellhb2b@gmail.com', 'New Organization Fact Sheet', $fact_sheet_html, $fact_sheet_plain)) {
                    echo json_encode(['success' => true, 'message' => 'Approved and fact sheet sent.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Approved, but fact sheet email failed.']);
                }
                // Send approval email to initial user
                $approval_subject = "Account Approved";
                $approval_html = '<html><body style="font-family: Arial; color: #333;"><div style="max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd;"><img src="https://' . $_SERVER['HTTP_HOST'] . '/icons/logo-192.png" alt="Logo" style="max-width: 150px;"><h2>Account Approved</h2><p>Your account has been approved. You can now login at test.resupplyrocket.com/login.php. Please add to your home screen for easy access.</p></div></body></html>';
                $approval_plain = "Your account has been approved. You can now login at test.resupplyrocket.com/login.php. Please add to your home screen for easy access.";
                send_email($initial_user_email, $approval_subject, $approval_html, $approval_plain);
            } else {
                echo json_encode(['success' => false, 'message' => 'Not pending or not found.']);
            }
        } elseif ($action === 'suspend') {
            $stmt = $pdo->prepare("UPDATE organizations SET suspended = 1 WHERE id = :id");
            $stmt->execute(['id' => $organization_id]);
            $stmt = $pdo->prepare("UPDATE users SET suspended = 1 WHERE organization_id = :organization_id");
            $stmt->execute(['organization_id' => $organization_id]);
            echo json_encode(['success' => true, 'message' => 'Suspended.']);
        } elseif ($action === 'unsuspend') {
            $stmt = $pdo->prepare("UPDATE organizations SET suspended = 0 WHERE id = :id");
            $stmt->execute(['id' => $organization_id]);
            $stmt = $pdo->prepare("UPDATE users SET suspended = 0 WHERE organization_id = :organization_id");
            $stmt->execute(['organization_id' => $organization_id]);
            echo json_encode(['success' => true, 'message' => 'Unsuspended.']);
        } elseif ($action === 'delete') {
            error_log("Deleting organization $organization_id");
            // Delete associated users
            $stmt = $pdo->prepare("DELETE FROM users WHERE organization_id = :id");
            $stmt->execute(['id' => $organization_id]);
            error_log("Deleted " . $stmt->rowCount() . " users for organization $organization_id");
            // Delete organization
            $stmt = $pdo->prepare("DELETE FROM organizations WHERE id = :id");
            $stmt->execute(['id' => $organization_id]);
            error_log("Deleted organization $organization_id, rows affected: " . $stmt->rowCount());
            echo json_encode(['success' => true, 'message' => 'Deleted.']);
        } elseif ($action === 'edit') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $physical_address = filter_input(INPUT_POST, 'physical_address', FILTER_SANITIZE_STRING);
            $billing_address = filter_input(INPUT_POST, 'billing_address', FILTER_SANITIZE_STRING);
            $contact_name = filter_input(INPUT_POST, 'contact_name', FILTER_SANITIZE_STRING);
            $contact_email = filter_input(INPUT_POST, 'contact_email', FILTER_SANITIZE_EMAIL);
            $organization_account_number = filter_input(INPUT_POST, 'organization_account_number', FILTER_SANITIZE_STRING);
            $organization_type = $_POST['organization_type'] ?? 'retail';
            $organization_resale_number = filter_input(INPUT_POST, 'organization_resale_number', FILTER_SANITIZE_STRING);
            $organization_authorized_people = json_decode($_POST['organization_authorized_people'], true) ?: [];
            $is_propane = isset($_POST['is_propane']) ? 1 : 0;
            $stmt = $pdo->prepare("UPDATE organizations SET name = :name, address = :physical, mailing_address = :billing, contact_name = :contact_name, contact_email = :contact_email, organization_account_number = :organization_account_number, organization_type = :organization_type, organization_resale_number = :organization_resale_number, organization_authorized_people = :auth, is_propane = :is_propane WHERE id = :id");
            $stmt->execute([
                'name' => $name,
                'physical' => $physical_address,
                'billing' => $billing_address,
                'contact_name' => $contact_name,
                'contact_email' => $contact_email,
                'organization_account_number' => $organization_account_number,
                'organization_type' => $organization_type,
                'organization_resale_number' => $organization_resale_number,
                'auth' => json_encode($organization_authorized_people),
                'is_propane' => $is_propane,
                'id' => $organization_id
            ]);
            echo json_encode(['success' => true, 'message' => 'Updated.']);
        } elseif ($action === 'add') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $physical_address = filter_input(INPUT_POST, 'physical_address', FILTER_SANITIZE_STRING);
            $billing_address = filter_input(INPUT_POST, 'billing_address', FILTER_SANITIZE_STRING);
            $contact_name = filter_input(INPUT_POST, 'contact_name', FILTER_SANITIZE_STRING);
            $contact_email = filter_input(INPUT_POST, 'contact_email', FILTER_SANITIZE_EMAIL);
            $organization_account_number = filter_input(INPUT_POST, 'organization_account_number', FILTER_SANITIZE_STRING);
            $organization_type = $_POST['organization_type'] ?? 'retail';
            $organization_resale_number = filter_input(INPUT_POST, 'organization_resale_number', FILTER_SANITIZE_STRING);
            $organization_authorized_people = json_decode($_POST['organization_authorized_people'], true) ?: [];
            $approval_status = 'approved'; // Admin-added auto-approved
            $stmt = $pdo->prepare("INSERT INTO organizations (name, address, mailing_address, contact_name, contact_email, organization_account_number, organization_type, organization_resale_number, organization_authorized_people, approval_status) VALUES (:name, :physical, :billing, :contact_name, :contact_email, :organization_account_number, :organization_type, :organization_resale_number, :auth, :approval)");
            $stmt->execute([
                'name' => $name,
                'physical' => $physical_address,
                'billing' => $billing_address,
                'contact_name' => $contact_name,
                'contact_email' => $contact_email,
                'organization_account_number' => $organization_account_number,
                'organization_type' => $organization_type,
                'organization_resale_number' => $organization_resale_number,
                'auth' => json_encode($organization_authorized_people),
                'approval' => $approval_status
            ]);
            echo json_encode(['success' => true, 'message' => 'Added.']);
        } elseif ($action === 'fetch_details') {
            $stmt = $pdo->prepare("SELECT * FROM organizations WHERE id = :id");
            $stmt->execute(['id' => $organization_id]);
            $organization = $stmt->fetch();
            if ($organization) {
                $organization['organization_authorized_people'] = json_decode($organization['organization_authorized_people'] ?? '[]', true);
                echo json_encode(['success' => true, 'organization' => $organization]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Not found.']);
            }
        }
    } catch (PDOException $e) {
        error_log("Organization action error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// Fetch organizations
$stmt = $pdo->query("SELECT o.*, (SELECT COUNT(*) FROM users u WHERE u.organization_id = o.id) AS user_count, (SELECT MAX(o2.created_at) FROM orders o2 JOIN users u ON o2.user_id = u.id WHERE u.organization_id = o.id) AS last_order_date FROM organizations o ORDER BY o.id DESC");
$organizations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage Organizations</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Manage Organizations</h1>
        <div id="responseMessage"></div>
        <!-- Add New Organization Form -->
        <div class="card mb-3">
            <div class="card-body">
                <h3>Add New Organization</h3>
                <form id="addOrganizationForm">
                    <label>Name:</label><input type="text" name="name" class="form-control" required><br>
                    <label>Physical Address:</label><textarea name="physical_address" class="form-control" id="add_physical" required></textarea><br>
                    <label><input type="checkbox" id="add_same" checked> Billing same?</label><br>
                    <label>Billing Address:</label><textarea name="billing_address" class="form-control" id="add_billing" required></textarea><br>
                    <label>Contact Name:</label><input type="text" name="contact_name" class="form-control" required><br>
                    <label>Contact Email:</label><input type="email" name="contact_email" class="form-control" required><br>
                    <label>Account Number:</label><input type="text" name="organization_account_number" class="form-control" required><br>
                    <label>Type:</label>
                    <select name="organization_type" class="form-control">
                        <option value="retail">Retail</option>
                        <option value="wholesale">Wholesale</option>
                    </select><br>
                    <label>Resale Number (if wholesale):</label><input type="text" name="organization_resale_number" class="form-control"><br>
                    <label>Authorized People:</label>
                    <div id="add_auth_rows">
                        <div class="auth_row">
                            <input type="text" placeholder="Name" class="form-control d-inline-block w-50">
                            <input type="email" placeholder="Email" class="form-control d-inline-block w-50">
                        </div>
                    </div>
                    <button type="button" id="add_add_auth">Add Another</button>
                    <input type="hidden" name="organization_authorized_people" id="add_authorized_json">
                    <button type="submit" class="btn btn-primary w-100">Add Organization</button>
                </form>
            </div>
        </div>
        <!-- Organizations List -->
        <div class="card">
            <div class="card-body">
                <h3>Organizations List</h3>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Signup Date</th>
                                <th>Last Order</th>
                                <th>User Count</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($organizations as $organization): ?>
                                <tr data-organization-id="<?php echo $organization['id']; ?>">
                                    <td><?php echo $organization['id']; ?></td>
                                    <td><?php echo htmlspecialchars($organization['name']); ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($organization['created_at'])); ?></td>
                                    <td><?php echo $organization['last_order_date'] ? date('Y-m-d', strtotime($organization['last_order_date'])) : 'N/A'; ?></td>
                                    <td><?php echo $organization['user_count']; ?></td>
                                    <td><?php echo htmlspecialchars($organization['approval_status']) . ($organization['suspended'] ? ' (Suspended)' : ''); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-organization" data-bs-toggle="modal" data-bs-target="#editOrganizationModal">Edit</button>
                                        <?php if ($organization['approval_status'] === 'pending'): ?>
                                            <button class="btn btn-sm btn-success approve-organization" data-bs-toggle="modal" data-bs-target="#approveOrganizationModal">Approve</button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-warning suspend-organization">Suspend</button>
                                        <button class="btn btn-sm btn-info unsuspend-organization" style="display:none;">Unsuspend</button>
                                        <button class="btn btn-sm btn-danger delete-organization">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Edit Modal -->
        <div class="modal fade" id="editOrganizationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Organization</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editOrganizationForm">
                            <input type="hidden" name="organization_id" id="edit_organization_id">
                            <label>Name:</label><input type="text" name="name" id="edit_name" class="form-control"><br>
                            <label>Physical Address:</label><textarea name="physical_address" id="edit_physical" class="form-control"></textarea><br>
                            <label><input type="checkbox" id="edit_same" checked> Billing same?</label><br>
                            <label>Billing Address:</label><textarea name="billing_address" id="edit_billing" class="form-control"></textarea><br>
                            <label>Contact Name:</label><input type="text" name="contact_name" id="edit_contact_name" class="form-control"><br>
                            <label>Contact Email:</label><input type="email" name="contact_email" id="edit_contact_email" class="form-control"><br>
                            <label>Account Number:</label><input type="text" name="organization_account_number" id="edit_organization_account_number" class="form-control"><br>
                            <label>Type:</label>
                            <select name="organization_type" id="edit_organization_type" class="form-control">
                                <option value="retail">Retail</option>
                                <option value="wholesale">Wholesale</option>
                            </select><br>
                            <label>Resale Number:</label><input type="text" name="organization_resale_number" id="edit_organization_resale_number" class="form-control"><br>
                            <label>Authorized People:</label>
                            <div id="edit_auth_rows"></div>
                            <button type="button" id="edit_add_auth">Add Another</button>
                            <input type="hidden" name="organization_authorized_people" id="edit_authorized_json">
                            <label><input type="checkbox" name="is_propane" id="edit_is_propane"> Propane Organization (Redirect Users to Propane Order on Login)</label><br>
                            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Approve Modal -->
        <div class="modal fade" id="approveOrganizationModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Organization</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="approveOrganizationForm">
                            <input type="hidden" name="organization_id" id="approve_organization_id">
                            <label>Assign Account Number:</label>
                            <input type="text" name="organization_account_number" id="approve_organization_account_number" class="form-control" required>
                            <button type="submit" class="btn btn-success w-100"><span class="spinner-border spinner-border-sm d-none" role="status"></span> Approve</button>
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
            // Add Organization
            $('#addOrganizationForm').submit(function(e) {
                e.preventDefault();
                let auth = [];
                $('#add_auth_rows .auth_row').each(function() {
                    let name = $(this).find('input[type="text"]').val();
                    let email = $(this).find('input[type="email"]').val();
                    if (name && email) auth.push({name: name, email: email});
                });
                $('#add_authorized_json').val(JSON.stringify(auth));
                if ($('#add_same').is(':checked')) {
                    $('#add_billing').val($('#add_physical').val());
                }
                $.post('admin_organizations.php', $(this).serialize() + '&action=add', function(response) {
                    $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                    if (response.success) location.reload();
                }, 'json');
            });
            $('#add_add_auth').click(function() {
                $('#add_auth_rows').append('<div class="auth_row"><input type="text" placeholder="Name" class="form-control d-inline-block w-50"><input type="email" placeholder="Email" class="form-control d-inline-block w-50"><button type="button" class="remove_row">Remove</button></div>');
            });
            $(document).on('click', '.remove_row', function() {
                $(this).parent().remove();
            });
            $('#add_same').change(function() {
                if (this.checked) {
                    $('#add_billing').val($('#add_physical').val()).prop('disabled', true);
                } else {
                    $('#add_billing').prop('disabled', false);
                }
            });
            $('#add_physical').on('input', function() {
                if ($('#add_same').is(':checked')) {
                    $('#add_billing').val(this.value);
                }
            });
            // Edit Organization Modal Populate
            $('.edit-organization').click(function() {
                let organizationId = $(this).closest('tr').data('organization-id');
                $.post('admin_organizations.php', { action: 'fetch_details', organization_id: organizationId }, function(response) {
                    if (response.success) {
                        let organization = response.organization;
                        $('#edit_organization_id').val(organization.id);
                        $('#edit_name').val(organization.name);
                        $('#edit_physical').val(organization.address);
                        $('#edit_billing').val(organization.mailing_address);
                        $('#edit_contact_name').val(organization.contact_name);
                        $('#edit_contact_email').val(organization.contact_email);
                        $('#edit_organization_account_number').val(organization.organization_account_number);
                        $('#edit_organization_type').val(organization.organization_type);
                        $('#edit_organization_resale_number').val(organization.organization_resale_number);
                        $('#edit_auth_rows').html('');
                        organization.organization_authorized_people.forEach(p => {
                            $('#edit_auth_rows').append('<div class="auth_row"><input type="text" value="' + p.name + '" class="form-control d-inline-block w-50"><input type="email" value="' + p.email + '" class="form-control d-inline-block w-50"><button type="button" class="remove_row">Remove</button></div>');
                        });
                        $('#edit_is_propane').prop('checked', organization.is_propane === 1);
                        $('#edit_same').prop('checked', organization.address === organization.mailing_address).change();
                    } else {
                        alert('Error fetching organization.');
                    }
                }, 'json');
            });
            $('#editOrganizationForm').submit(function(e) {
                e.preventDefault();
                let auth = [];
                $('#edit_auth_rows .auth_row').each(function() {
                    let name = $(this).find('input[type="text"]').val();
                    let email = $(this).find('input[type="email"]').val();
                    if (name && email) auth.push({name: name, email: email});
                });
                $('#edit_authorized_json').val(JSON.stringify(auth));
                if ($('#edit_same').is(':checked')) {
                    $('#edit_billing').val($('#edit_physical').val());
                }
                $.post('admin_organizations.php', $(this).serialize() + '&action=edit', function(response) {
                    $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                    if (response.success) {
                        $('#editOrganizationModal').modal('hide');
                        location.reload();
                    }
                }, 'json');
            });
            $('#edit_add_auth').click(function() {
                $('#edit_auth_rows').append('<div class="auth_row"><input type="text" placeholder="Name" class="form-control d-inline-block w-50"><input type="email" placeholder="Email" class="form-control d-inline-block w-50"><button type="button" class="remove_row">Remove</button></div>');
            });
            $('#edit_same').change(function() {
                if (this.checked) {
                    $('#edit_billing').val($('#edit_physical').val()).prop('disabled', true);
                } else {
                    $('#edit_billing').prop('disabled', false);
                }
            });
            $('#edit_physical').on('input', function() {
                if ($('#edit_same').is(':checked')) {
                    $('#edit_billing').val(this.value);
                }
            });
            // Approve
            $('.approve-organization').click(function() {
                let organizationId = $(this).closest('tr').data('organization-id');
                $('#approve_organization_id').val(organizationId);
                $('#approveOrganizationModal').modal('show');
            });
            $('#approveOrganizationForm').submit(function(e) {
                e.preventDefault();
                let $button = $(this).find('button[type="submit"]');
                let $spinner = $button.find('.spinner-border');
                $spinner.removeClass('d-none');
                $button.prop('disabled', true);
                $.post('admin_organizations.php', $(this).serialize() + '&action=approve', function(response) {
                    $spinner.addClass('d-none');
                    $button.prop('disabled', false);
                    $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                    if (response.success) {
                        $('#approveOrganizationModal').modal('hide');
                        location.reload();
                    }
                }, 'json');
            });
            // Suspend
            $('.suspend-organization').click(function() {
                let organizationId = $(this).closest('tr').data('organization-id');
                if (confirm('Suspend organization?')) {
                    $.post('admin_organizations.php', { action: 'suspend', organization_id: organizationId }, function(response) {
                        if (response.success) location.reload();
                        else alert(response.message);
                    }, 'json');
                }
            });
            // Unsuspend
            $('.unsuspend-organization').click(function() {
                let organizationId = $(this).closest('tr').data('organization-id');
                if (confirm('Unsuspend organization?')) {
                    $.post('admin_organizations.php', { action: 'unsuspend', organization_id: organizationId }, function(response) {
                        if (response.success) location.reload();
                        else alert(response.message);
                    }, 'json');
                }
            });
            // Delete
            $('.delete-organization').click(function() {
                let organizationId = $(this).closest('tr').data('organization-id');
                if (confirm('Delete organization?')) {
                    $.post('admin_organizations.php', { action: 'delete', organization_id: organizationId }, function(response) {
                        if (response.success) location.reload();
                        else alert(response.message);
                    }, 'json');
                }
            });
        });
    </script>
</body>
</html>
