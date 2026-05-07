<?php
// admin_shopping_lists.php – Modified March 11, 2025 20:45 PDT – Lines: 410
require_once 'config.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing admin_shopping_lists.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

// Handle set organization
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['set_organization'])) {
    $_SESSION['selected_organization_id'] = intval($_POST['set_organization']);
    echo json_encode(['success' => true]);
    exit;
}

// Handle organization selection change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_organization'])) {
    unset($_SESSION['selected_organization_id']);
    echo json_encode(['success' => true]);
    exit;
}

// Handle AJAX for shopping list actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];
    $organization_id = intval($_POST['organization_id'] ?? 0);
    try {
        if ($action === 'create_list') {
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $stmt = $pdo->prepare("INSERT INTO shopping_lists (organization_id, name) VALUES (:organization_id, :name)");
            $stmt->execute(['organization_id' => $organization_id, 'name' => $name]);
            echo json_encode(['success' => true, 'list_id' => $pdo->lastInsertId()]);
        } elseif ($action === 'delete_list') {
            $list_id = intval($_POST['list_id']);
            $stmt = $pdo->prepare("DELETE FROM shopping_lists WHERE id = :id AND organization_id = :organization_id");
            $stmt->execute(['id' => $list_id, 'organization_id' => $organization_id]);
            echo json_encode(['success' => true]);
        } elseif ($action === 'bulk_add_to_list') {
            $list_id = intval($_POST['list_id']);
            $item_ids = json_decode($_POST['item_ids'] ?? '[]', true);
            $max_order = 0;
            $stmt = $pdo->prepare("SELECT MAX(item_order) AS max_order FROM shopping_list_items WHERE list_id = :list");
            $stmt->execute(['list' => $list_id]);
            $max_order = $stmt->fetch()['max_order'] ?? 0;
            foreach ($item_ids as $item_id) {
                $max_order++;
                $stmt = $pdo->prepare("INSERT IGNORE INTO shopping_list_items (list_id, catalog_item_id, item_order) VALUES (:list, :item, :order)");
                $stmt->execute(['list' => $list_id, 'item' => intval($item_id), 'order' => $max_order]);
            }
            echo json_encode(['success' => true]);
        } elseif ($action === 'bulk_remove_from_list') {
            $list_id = intval($_POST['list_id']);
            $item_ids = json_decode($_POST['item_ids'] ?? '[]', true);
            foreach ($item_ids as $item_id) {
                $stmt = $pdo->prepare("DELETE FROM shopping_list_items WHERE list_id = :list AND catalog_item_id = :item");
                $stmt->execute(['list' => $list_id, 'item' => intval($item_id)]);
            }
            echo json_encode(['success' => true]);
        } elseif ($action === 'remove_from_list') {
            $list_id = intval($_POST['list_id']);
            $item_id = intval($_POST['item_id']);
            $stmt = $pdo->prepare("DELETE FROM shopping_list_items WHERE list_id = :list AND catalog_item_id = :item");
            $stmt->execute(['list' => $list_id, 'item' => $item_id]);
            echo json_encode(['success' => true]);
        } elseif ($action === 'update_order') {
            $list_id = intval($_POST['list_id']);
            $order = json_decode($_POST['order'] ?? '[]', true);
            foreach ($order as $idx => $item_id) {
                $stmt = $pdo->prepare("UPDATE shopping_list_items SET item_order = :order WHERE list_id = :list AND catalog_item_id = :item");
                $stmt->execute(['order' => $idx + 1, 'list' => $list_id, 'item' => intval($item_id)]);
            }
            echo json_encode(['success' => true]);
        } elseif ($action === 'load_lists') {
            $stmt = $pdo->prepare("SELECT * FROM shopping_lists WHERE organization_id = :organization_id ORDER BY name");
            $stmt->execute(['organization_id' => $organization_id]);
            $lists = $stmt->fetchAll();
            $data = [];
            foreach ($lists as $list) {
                $stmt = $pdo->prepare("SELECT ci.*, sli.item_order, COALESCE(cio.custom_price, ci.price) AS effective_price FROM shopping_list_items sli JOIN catalog_items ci ON sli.catalog_item_id = ci.id LEFT JOIN organization_item_overrides cio ON ci.id = cio.catalog_item_id AND cio.organization_id = :organization_id WHERE sli.list_id = :list ORDER BY sli.item_order");
                $stmt->execute(['list' => $list['id'], 'organization_id' => $organization_id]);
                $data[$list['id']] = ['name' => $list['name'], 'items' => $stmt->fetchAll()];
            }
            echo json_encode(['success' => true, 'lists' => $data]);
        } elseif ($action === 'load_organization_items') {
            $stmt = $pdo->prepare("SELECT ci.*, COALESCE(cio.custom_price, ci.price) AS effective_price FROM organization_item_overrides cio JOIN catalog_items ci ON cio.catalog_item_id = ci.id WHERE cio.organization_id = :organization_id ORDER BY ci.category, ci.item_name");
            $stmt->execute(['organization_id' => $organization_id]);
            echo json_encode(['success' => true, 'items' => $stmt->fetchAll()]);
        } elseif ($action === 'search_organization_items') {
            $query = '%' . filter_input(INPUT_POST, 'query', FILTER_SANITIZE_STRING) . '%';
            $stmt = $pdo->prepare("SELECT ci.*, COALESCE(cio.custom_price, ci.price) AS effective_price FROM organization_item_overrides cio JOIN catalog_items ci ON cio.catalog_item_id = ci.id WHERE cio.organization_id = :organization_id AND (ci.item_name LIKE :query OR ci.sku LIKE :query) ORDER BY ci.item_name LIMIT 50");
            $stmt->execute(['organization_id' => $organization_id, 'query' => $query]);
            echo json_encode(['success' => true, 'items' => $stmt->fetchAll()]);
        }
    } catch (PDOException $e) {
        error_log("Shopping lists action error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
    exit;
}

// Fetch organizations
$stmt = $pdo->query("SELECT id, name FROM organizations WHERE approval_status = 'approved' ORDER BY name");
$organizations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Shopping Lists</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Manage Shopping Lists</h1>
        <div id="responseMessage"></div>
        <?php if (isset($_SESSION['selected_organization_id'])): 
            $selected_id = $_SESSION['selected_organization_id'];
            $stmt = $pdo->prepare("SELECT name FROM organizations WHERE id = :id");
            $stmt->execute(['id' => $selected_id]);
            $selected_organization = $stmt->fetch();
        ?>
            <p>Selected Organization: <?php echo htmlspecialchars($selected_organization['name']); ?></p>
            <button id="changeOrganizationBtn" class="btn btn-secondary mb-3">Change Organization</button>
            <script>var currentOrganizationId = <?php echo $selected_id; ?>;</script>
        <?php else: ?>
            <label>Select Organization:</label>
            <select id="organizationSelect" class="form-control mb-3">
                <option value="">-- Select --</option>
                <?php foreach ($organizations as $organization): ?>
                    <option value="<?php echo $organization['id']; ?>"><?php echo htmlspecialchars($organization['name']); ?></option>
                <?php endforeach; ?>
            </select>
            <script>var currentOrganizationId = 0;</script>
        <?php endif; ?>
        <div class="d-flex justify-content-between mb-3">
            <a href="admin_shopping_lists.php" class="btn btn-primary me-2">Shopping Lists</a>
            <a href="admin_organization_catalog.php" class="btn btn-outline-primary me-2">Organization Catalog</a>
            <a href="admin_dashboard.php" class="btn btn-outline-secondary">Dashboard</a>
        </div>
        <button id="createListBtn" class="btn btn-primary mb-3" disabled>Create New List</button>
        <div id="listsAccordion" class="accordion"></div>
        <div class="modal fade" id="addItemsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Items to List</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="searchOrganizationItems" placeholder="Search organization items" class="form-control mb-2">
                        <button id="bulkAddToListBtn" class="btn btn-primary mb-2" disabled>Bulk Add Selected</button>
                        <div class="table-responsive">
                            <table class="table table-striped" id="organizationItemsTable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAllOrganization"></th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>SKU</th>
                                        <th>Price</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="admin_dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    </div>
    <script>
        $(document).ready(function() {
            // Handle organization selection
            $('#organizationSelect').change(function() {
                let selectedId = $(this).val();
                $.post('admin_shopping_lists.php', { set_organization: selectedId }, function(response) {
                    if (response.success) location.reload();
                }, 'json');
            });
            // Change organization button
            $('#changeOrganizationBtn').click(function() {
                $.post('admin_shopping_lists.php', { change_organization: 1 }, function(response) {
                    if (response.success) location.reload();
                }, 'json');
            });
            // Load data if organization selected
            if (currentOrganizationId > 0) {
                loadLists();
                $('#createListBtn').prop('disabled', false);
            }
            $('#createListBtn').click(function() {
                let name = prompt('Enter list name:');
                if (name) {
                    $.post('admin_shopping_lists.php', { action: 'create_list', organization_id: currentOrganizationId, name: name }, function(response) {
                        if (response.success) loadLists();
                    }, 'json');
                }
            });
            $('#searchOrganizationItems').on('input', function() {
                let query = $(this).val();
                if (query.length >= 3) {
                    $.post('admin_shopping_lists.php', { action: 'search_organization_items', organization_id: currentOrganizationId, query: query }, function(response) {
                        if (response.success) updateOrganizationItemsTable(response.items);
                    }, 'json');
                } else {
                    loadOrganizationItems();
                }
            });
            $('#selectAllOrganization').change(function() {
                $('#organizationItemsTable .select-item').prop('checked', this.checked);
                toggleBulkAddToList();
            });
            $(document).on('change', '.select-item', toggleBulkAddToList);
            $('#bulkAddToListBtn').click(function() {
                let selected = [];
                $('#organizationItemsTable .select-item:checked').each(function() {
                    selected.push($(this).data('id'));
                });
                $.post('admin_shopping_lists.php', { action: 'bulk_add_to_list', list_id: currentListId, item_ids: JSON.stringify(selected) }, function(response) {
                    if (response.success) {
                        $('#addItemsModal').modal('hide');
                        loadLists();
                    }
                }, 'json');
            });
            $(document).on('click', '.add-items', function() {
                currentListId = $(this).data('list-id');
                loadOrganizationItems();
                $('#addItemsModal').modal('show');
            });
            $(document).on('click', '.delete-list', function() {
                let listId = $(this).data('list-id');
                if (confirm('Delete list?')) {
                    $.post('admin_shopping_lists.php', { action: 'delete_list', organization_id: currentOrganizationId, list_id: listId }, function(response) {
                        if (response.success) loadLists();
                    }, 'json');
                }
            });
            $(document).on('click', '.bulk-remove-items', function() {
                let listId = $(this).data('list-id');
                let selected = [];
                $(this).closest('.accordion-body').find('.select-list-item:checked').each(function() {
                    selected.push($(this).data('item-id'));
                });
                if (selected.length > 0 && confirm('Remove selected items from list?')) {
                    $.post('admin_shopping_lists.php', { action: 'bulk_remove_from_list', list_id: listId, item_ids: JSON.stringify(selected) }, function(response) {
                        if (response.success) loadLists();
                    }, 'json');
                }
            });
            $(document).on('change', '.select-all-items', function() {
                let checked = this.checked;
                $(this).closest('.table').find('.select-list-item').prop('checked', checked);
                toggleBulkRemove(this);
            });
            $(document).on('change', '.select-list-item', function() {
                toggleBulkRemove(this);
            });
        });
        function toggleBulkRemove(el) {
            let body = $(el).closest('.accordion-body');
            let btn = body.find('.bulk-remove-items');
            btn.prop('disabled', body.find('.select-list-item:checked').length === 0);
        }
        function loadOrganizationItems() {
            $.post('admin_shopping_lists.php', { action: 'load_organization_items', organization_id: currentOrganizationId }, function(response) {
                if (response.success) updateOrganizationItemsTable(response.items);
            }, 'json');
        }
        function updateOrganizationItemsTable(items) {
            let html = '';
            items.forEach(item => {
                html += '<tr><td><input type="checkbox" class="select-item" data-id="' + item.id + '"></td><td><img src="' + (item.image_url || 'images/placeholder.jpg') + '" style="width:50px;"></td><td>' + item.item_name + '</td><td>' + item.description + '</td><td>' + item.sku + '</td><td>' + item.effective_price + '</td></tr>';
            });
            $('#organizationItemsTable tbody').html(html);
        }
        function toggleBulkAddToList() {
            $('#bulkAddToListBtn').prop('disabled', $('#organizationItemsTable .select-item:checked').length === 0);
        }
        function loadLists() {
            $.post('admin_shopping_lists.php', { action: 'load_lists', organization_id: currentOrganizationId }, function(response) {
                if (response.success) {
                    let html = '';
                    Object.keys(response.lists).forEach(listId => {
                        let list = response.lists[listId];
                        html += '<div class="accordion-item"><h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#list' + listId + '">' + list.name + '</button></h2>';
                        html += '<div id="list' + listId + '" class="accordion-collapse collapse"><div class="accordion-body">';
                        html += '<div class="d-flex mb-2"><button class="btn btn-sm btn-primary add-items me-2" data-list-id="' + listId + '">Add Items</button><button class="btn btn-sm btn-danger delete-list me-2" data-list-id="' + listId + '">Delete List</button><button class="btn btn-sm btn-danger bulk-remove-items" data-list-id="' + listId + '" disabled>Bulk Remove Selected</button></div>';
                        html += '<div class="table-responsive mt-2"><table class="table table-striped sortable"><thead><tr><th><input type="checkbox" class="select-all-items"></th><th>Image</th><th>Name</th><th>Description</th><th>SKU</th><th>Price</th><th>Actions</th></tr></thead><tbody>';
                        list.items.forEach(item => {
                            html += '<tr data-item-id="' + item.id + '"><td><input type="checkbox" class="select-list-item" data-item-id="' + item.id + '"></td><td><img src="' + (item.image_url || 'images/placeholder.jpg') + '" style="width:50px;"></td><td>' + item.item_name + '</td><td>' + item.description + '</td><td>' + item.sku + '</td><td>' + item.effective_price + '</td><td><button class="btn btn-sm btn-danger remove-from-list" data-item-id="' + item.id + '">Remove</button></td></tr>';
                        });
                        html += '</tbody></table></div></div></div></div>';
                    });
                    $('#listsAccordion').html(html);
                    $('.sortable tbody').each(function() {
                        new Sortable(this, {
                            animation: 150,
                            onEnd: function(evt) {
                                let order = [];
                                $(evt.to).find('tr').each(function() {
                                    order.push($(this).data('item-id'));
                                });
                                let listId = $(evt.to).closest('.accordion-item').find('.add-items').data('list-id');
                                $.post('admin_shopping_lists.php', { action: 'update_order', list_id: listId, order: JSON.stringify(order) }, function(response) {
                                    if (!response.success) alert('Error updating order.');
                                }, 'json');
                            }
                        });
                    });
                }
            }, 'json');
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
