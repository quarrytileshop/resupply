<?php
// checkbox_view.php - Modification Date: August 22, 2025, 12:30 PM - Total Lines: 191
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT company_id FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();
$company_id = $user['company_id'];
$list_id = intval($_GET['id']);
$stmt = $pdo->prepare("SELECT * FROM checkbox_lists WHERE id = :id AND company_id = :company AND archived = 0");
$stmt->execute(['id' => $list_id, 'company' => $company_id]);
$list = $stmt->fetch();
if (!$list) {
    die("List not found or access denied.");
}
$is_creator = $list['creator_id'] == $user_id;
$items = json_decode($list['items'], true);
// Handle get_items for polling
if (isset($_GET['get_items'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'items' => $items]);
    exit;
}
// Handle check update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_check'])) {
    $index = intval($_POST['index']);
    $checked = intval($_POST['checked']);
    $items[$index]['checked'] = (bool)$checked;
    $stmt = $pdo->prepare("UPDATE checkbox_lists SET items = :items WHERE id = :id");
    $stmt->execute(['items' => json_encode($items), 'id' => $list_id]);
    echo json_encode(['success' => true]);
    exit;
}
// Handle edit item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_item']) && $is_creator) {
    $index = intval($_POST['index']);
    $field = $_POST['field'];
    $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING);
    if ($field == 'quantity') $value = intval($value);
    $items[$index][$field] = $value;
    $stmt = $pdo->prepare("UPDATE checkbox_lists SET items = :items WHERE id = :id");
    $stmt->execute(['items' => json_encode($items), 'id' => $list_id]);
    echo json_encode(['success' => true]);
    exit;
}
// Handle add item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_item']) && $is_creator) {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $quantity = intval($_POST['quantity']);
    $sku = filter_input(INPUT_POST, 'sku', FILTER_SANITIZE_STRING);
    $items[] = ['name' => $name, 'quantity' => $quantity, 'sku' => $sku, 'checked' => false];
    $stmt = $pdo->prepare("UPDATE checkbox_lists SET items = :items WHERE id = :id");
    $stmt->execute(['items' => json_encode($items), 'id' => $list_id]);
    echo json_encode(['success' => true]);
    exit;
}
// Handle remove item
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_item']) && $is_creator) {
    $index = intval($_POST['index']);
    unset($items[$index]);
    $items = array_values($items);
    $stmt = $pdo->prepare("UPDATE checkbox_lists SET items = :items WHERE id = :id");
    $stmt->execute(['items' => json_encode($items), 'id' => $list_id]);
    echo json_encode(['success' => true]);
    exit;
}
// Handle archive
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['archive_list']) && $is_creator) {
    $stmt = $pdo->prepare("UPDATE checkbox_lists SET archived = 1 WHERE id = :id AND creator_id = :creator");
    $stmt->execute(['id' => $list_id, 'creator' => $user_id]);
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Checkbox List: <?php echo htmlspecialchars($list['name']); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1><?php echo htmlspecialchars($list['name']); ?></h1>
        <div class="table-responsive">
            <table class="table table-striped" id="checkboxTable">
                <thead>
                    <tr>
                        <th>Checked</th>
                        <th>Name/Description</th>
                        <th>Quantity</th>
                        <th>SKU</th>
                        <?php if ($is_creator): ?><th>Actions</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $idx => $item): ?>
                        <tr data-index="<?php echo $idx; ?>">
                            <td><input type="checkbox" class="check-item" <?php if ($item['checked']) echo 'checked'; ?>></td>
                            <td contenteditable="<?php echo $is_creator ? 'true' : 'false'; ?>" class="edit-field" data-field="name"><?php echo htmlspecialchars($item['name']); ?></td>
                            <td contenteditable="<?php echo $is_creator ? 'true' : 'false'; ?>" class="edit-field" data-field="quantity"><?php echo $item['quantity']; ?></td>
                            <td contenteditable="<?php echo $is_creator ? 'true' : 'false'; ?>" class="edit-field" data-field="sku"><?php echo htmlspecialchars($item['sku'] ?? ''); ?></td>
                            <?php if ($is_creator): ?>
                                <td><button class="btn btn-danger remove-item">Remove</button></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if ($is_creator): ?>
            <h3>Add Item</h3>
            <div class="row">
                <div class="col-md-4"><input type="text" id="new_name" placeholder="Name" class="form-control"></div>
                <div class="col-md-3"><input type="number" id="new_quantity" value="1" min="1" class="form-control"></div>
                <div class="col-md-3"><input type="text" id="new_sku" placeholder="SKU (optional)" class="form-control"></div>
                <div class="col-md-2"><button id="addItemBtn" class="btn btn-primary">Add</button></div>
            </div>
            <form method="post">
                <button type="submit" name="archive_list" class="btn btn-danger mt-3">Archive List</button>
            </form>
        <?php endif; ?>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <script>
        $(document).ready(function() {
            $('.check-item').change(function() {
                let index = $(this).closest('tr').data('index');
                let checked = this.checked ? 1 : 0;
                $.post('checkbox_view.php?id=<?php echo $list_id; ?>', {update_check: 1, index: index, checked: checked});
            });
            <?php if ($is_creator): ?>
                $('.edit-field').on('blur', function() {
                    let index = $(this).closest('tr').data('index');
                    let field = $(this).data('field');
                    let value = $(this).text().trim();
                    if (field === 'quantity') value = parseInt(value) || 1;
                    $.post('checkbox_view.php?id=<?php echo $list_id; ?>', {edit_item: 1, index: index, field: field, value: value});
                });
                $('#addItemBtn').click(function() {
                    let name = $('#new_name').val();
                    let quantity = parseInt($('#new_quantity').val()) || 1;
                    let sku = $('#new_sku').val();
                    if (name) {
                        $.post('checkbox_view.php?id=<?php echo $list_id; ?>', {add_item: 1, name: name, quantity: quantity, sku: sku}, function() {
                            location.reload();
                        });
                    }
                });
                $('.remove-item').click(function() {
                    let index = $(this).closest('tr').data('index');
                    if (confirm('Remove item?')) {
                        $.post('checkbox_view.php?id=<?php echo $list_id; ?>', {remove_item: 1, index: index}, function() {
                            location.reload();
                        });
                    }
                });
            <?php endif; ?>
            // Polling for real-time updates
            setInterval(function() {
                $.get('checkbox_view.php?id=<?php echo $list_id; ?>&get_items=1', function(response) {
                    if (response.success) {
                        let newItems = response.items;
                        let tbody = $('#checkboxTable tbody');
                        if (tbody.find('tr').length != newItems.length) {
                            location.reload(); // Reload if items added/removed
                            return;
                        }
                        tbody.find('tr').each(function(idx) {
                            let check = $(this).find('.check-item');
                            if (check.prop('checked') != newItems[idx].checked) {
                                check.prop('checked', newItems[idx].checked);
                            }
                            <?php if (!$is_creator): ?>return;<?php endif; ?> // Non-creators only update checks
                            $(this).find('.edit-field[data-field="name"]').text(newItems[idx].name);
                            $(this).find('.edit-field[data-field="quantity"]').text(newItems[idx].quantity);
                            $(this).find('.edit-field[data-field="sku"]').text(newItems[idx].sku || '');
                        });
                    }
                }, 'json').fail(function() {
                    console.error('Polling failed');
                });
            }, 5000); // Poll every 5s
        });
    </script>
</body>
</html>
