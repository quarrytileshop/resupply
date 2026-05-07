<?php
// checkbox_create.php - Modification Date: August 22, 2025, 1:00 PM - Total Lines: 290
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT company_id, username FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
$company_id = $user['company_id'];
$creator_name = $user['username'];
$list_id = intval($_GET['id'] ?? 0);
$is_edit = $list_id > 0;
if ($is_edit) {
    $stmt = $pdo->prepare("SELECT * FROM checkbox_lists WHERE id = :id AND company_id = :company AND archived = 0");
    $stmt->execute(['id' => $list_id, 'company' => $company_id]);
    $list = $stmt->fetch();
    if (!$list || $list['creator_id'] != $user_id) {
        die("Access denied or list not found.");
    }
    $items = json_decode($list['items'], true);
    $name = $list['name'];
} else {
    $items = [];
    $name = '';
}
// Fetch shopping lists
$stmt = $pdo->prepare("SELECT id, name FROM shopping_lists WHERE company_id = :company");
$stmt->execute(['company' => $company_id]);
$shopping_lists = $stmt->fetchAll();
// Handle pull from list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['pull_list'])) {
    header('Content-Type: application/json');
    $pull_id = intval($_POST['pull_list']);
    $stmt = $pdo->prepare("SELECT ci.item_name AS name, 1 AS quantity, ci.sku FROM shopping_list_items sli JOIN catalog_items ci ON sli.catalog_item_id = ci.id WHERE sli.list_id = :list");
    $stmt->execute(['list' => $pull_id]);
    echo json_encode(['success' => true, 'items' => $stmt->fetchAll()]);
    exit;
}
// Handle email link and alert
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email_link'])) {
    header('Content-Type: application/json');
    $link = filter_input(INPUT_POST, 'email_link', FILTER_SANITIZE_URL);
    $to = filter_input(INPUT_POST, 'to', FILTER_SANITIZE_EMAIL);
    $subject = "Checkbox List Link";
    $html_body = '<html><body><p>Here is the link to the checkbox list: <a href="' . $link . '">' . $link . '</a></p></body></html>';
    $plain_body = "Checkbox list link: " . $link;
    if (send_email($to, $subject, $html_body, $plain_body)) {
        // Create dashboard message
        $msg_content = "New Checkbox List: " . htmlspecialchars($name) . " shared by " . $creator_name . " - <a href='" . $link . "'>View here</a>";
        $stmt = $pdo->prepare("INSERT INTO messages (content, type) VALUES (:content, 'dismissable')");
        $stmt->execute(['content' => $msg_content]);
        $msg_id = $pdo->lastInsertId();
        // Link to all company users
        $stmt_users = $pdo->prepare("SELECT id FROM users WHERE company_id = :company");
        $stmt_users->execute(['company' => $company_id]);
        $users = $stmt_users->fetchAll();
        foreach ($users as $u) {
            $stmt_um = $pdo->prepare("INSERT INTO user_messages (user_id, message_id) VALUES (:user, :msg)");
            $stmt_um->execute(['user' => $u['id'], 'msg' => $msg_id]);
        }
        echo json_encode(['success' => true, 'message' => 'Email sent and alert created.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Email failed.']);
    }
    exit;
}
// Handle save
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_list'])) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $items_json = $_POST['items_json'];
    if ($is_edit) {
        $stmt = $pdo->prepare("UPDATE checkbox_lists SET name = :name, items = :items WHERE id = :id");
        $stmt->execute(['name' => $name, 'items' => $items_json, 'id' => $list_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO checkbox_lists (creator_id, company_id, name, items) VALUES (:creator, :company, :name, :items)");
        $stmt->execute(['creator' => $user_id, 'company' => $company_id, 'name' => $name, 'items' => $items_json]);
        $list_id = $pdo->lastInsertId();
    }
    header("Location: checkbox_view.php?id=$list_id");
    exit;
}
// Handle archive
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['archive_list'])) {
    $stmt = $pdo->prepare("UPDATE checkbox_lists SET archived = 1 WHERE id = :id AND creator_id = :creator");
    $stmt->execute(['id' => $list_id, 'creator' => $user_id]);
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $is_edit ? 'Edit' : 'Create'; ?> Checkbox List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1><?php echo $is_edit ? 'Edit' : 'Create'; ?> Checkbox List</h1>
        <form method="post" id="listForm">
            <label>Name:</label><input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" class="form-control" required><br>
            <h3>Items</h3>
            <div id="itemsTable" class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name/Description</th>
                            <th>Quantity</th>
                            <th>SKU (optional)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($items as $idx => $item): ?>
                            <tr>
                                <td><input type="text" class="form-control item-name" value="<?php echo htmlspecialchars($item['name']); ?>"></td>
                                <td><input type="number" class="form-control item-quantity" value="<?php echo $item['quantity']; ?>" min="1"></td>
                                <td><input type="text" class="form-control item-sku" value="<?php echo htmlspecialchars($item['sku'] ?? ''); ?>"></td>
                                <td><button type="button" class="btn btn-danger remove-item">Remove</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <button type="button" id="addManualItem" class="btn btn-secondary mb-2">Add Manual Item</button>
            <h3>Pull from Shopping Lists</h3>
            <select id="pullListSelect" class="form-control mb-2">
                <option value="">-- Select List --</option>
                <?php foreach ($shopping_lists as $list): ?>
                    <option value="<?php echo $list['id']; ?>"><?php echo htmlspecialchars($list['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" id="pullItemsBtn" class="btn btn-info mb-3" disabled>Pull Items</button>
            <input type="hidden" name="items_json" id="itemsJson">
            <button type="submit" name="save_list" class="btn btn-primary w-100">Save List</button>
        </form>
        <?php if ($is_edit): ?>
            <form method="post">
                <button type="submit" name="archive_list" class="btn btn-danger w-100 mt-2">Archive List</button>
            </form>
            <p>Shareable Link: <input type="text" value="<?php echo 'https://test.resupplyrocket.com/checkbox_view.php?id=' . $list_id; ?>" readonly class="form-control mt-2"></p>
            <button id="copyLinkBtn" class="btn btn-secondary">Copy Link</button>
            <button id="emailLinkBtn" class="btn btn-secondary">Email Link & Alert Company</button>
        <?php endif; ?>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <script>
        $(document).ready(function() {
            function updateJson() {
                let items = [];
                $('#itemsTable tbody tr').each(function() {
                    items.push({
                        name: $(this).find('.item-name').val(),
                        quantity: parseInt($(this).find('.item-quantity').val()) || 1,
                        sku: $(this).find('.item-sku').val(),
                        checked: false
                    });
                });
                $('#itemsJson').val(JSON.stringify(items));
            }
            $(document).on('input change', '.item-name, .item-quantity, .item-sku', updateJson);
            $('#addManualItem').click(function() {
                let row = '<tr><td><input type="text" class="form-control item-name"></td><td><input type="number" class="form-control item-quantity" min="1" value="1"></td><td><input type="text" class="form-control item-sku"></td><td><button type="button" class="btn btn-danger remove-item">Remove</button></td></tr>';
                $('#itemsTable tbody').append(row);
                updateJson();
            });
            $(document).on('click', '.remove-item', function() {
                $(this).closest('tr').remove();
                updateJson();
            });
            $('#pullListSelect').change(function() {
                $('#pullItemsBtn').prop('disabled', !this.value);
            });
            $('#pullItemsBtn').click(function() {
                let listId = $('#pullListSelect').val();
                $.post('checkbox_create.php', {pull_list: listId}, function(response) {
                    if (response.success) {
                        response.items.forEach(item => {
                            let row = '<tr><td><input type="text" class="form-control item-name" value="' + item.name + '"></td><td><input type="number" class="form-control item-quantity" value="' + item.quantity + '"></td><td><input type="text" class="form-control item-sku" value="' + (item.sku || '') + '"></td><td><button type="button" class="btn btn-danger remove-item">Remove</button></td></tr>';
                            $('#itemsTable tbody').append(row);
                        });
                        updateJson();
                    }
                }, 'json');
            });
            $('#copyLinkBtn').click(function() {
                let link = $('input[value^="https://test.resupplyrocket.com/checkbox_view.php"]').val();
                navigator.clipboard.writeText(link).then(() => alert('Link copied!'));
            });
            $('#emailLinkBtn').click(function() {
                let link = $('input[value^="https://test.resupplyrocket.com/checkbox_view.php"]').val();
                let email = prompt('Enter email to send link:');
                if (email) {
                    $.post('checkbox_create.php', {email_link: link, to: email}, function(response) {
                        alert(response.message);
                    }, 'json');
                }
            });
            updateJson(); // Initial
        });
    </script>
</body>
</html>
