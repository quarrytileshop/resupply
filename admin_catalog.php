<?php
// admin_catalog.php – Modified March 11, 2025 17:15 PDT – Lines: 500
require_once 'config.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing admin_catalog.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
session_start();
if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}
// Predefined categories
$predefined_categories = [
    'building materials',
    'cleaning',
    'electrical',
    'electronics',
    'fasteners',
    'garden',
    'hardware',
    'liquid paint',
    'paint supplies',
    'patio',
    'tools',
    'Uncategorized'
];
// Handle AJAX for catalog actions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    $action = $_POST['action'];
    $item_id = intval($_POST['item_id'] ?? 0);
    try {
        if ($action === 'add') {
            $item_name = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $sku = filter_input(INPUT_POST, 'sku', FILTER_SANITIZE_STRING);
            $price_input = trim($_POST['price'] ?? '');
            $price = $price_input === '' ? '0.00' : $price_input;
            if ($price !== '0.00' && !preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
                throw new Exception('Invalid price format.');
            }
            $price = number_format((float)$price, 2, '.', ''); // Format to 2 decimals as string
            $order_multiple = intval($_POST['order_multiple'] ?? 1);
            $item_type = $_POST['item_type'] ?? 'general';
            $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
            if ($category === 'Other') {
                $category = filter_input(INPUT_POST, 'custom_category', FILTER_SANITIZE_STRING);
            }
            // Handle image upload
            $image_url = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = 'product_images/';
                $target_file = $target_dir . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_url = $target_file;
                }
            }
            $stmt = $pdo->prepare("INSERT INTO catalog_items (item_name, description, sku, price, image_url, order_multiple, item_type, category) VALUES (:name, :desc, :sku, :price, :image, :multiple, :type, :cat)");
            $stmt->execute([
                'name' => $item_name,
                'desc' => $description,
                'sku' => $sku,
                'price' => $price,
                'image' => $image_url,
                'multiple' => $order_multiple,
                'type' => $item_type,
                'cat' => $category
            ]);
            echo json_encode(['success' => true, 'message' => 'Item added.']);
        } elseif ($action === 'edit') {
            $item_name = filter_input(INPUT_POST, 'item_name', FILTER_SANITIZE_STRING);
            $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
            $sku = filter_input(INPUT_POST, 'sku', FILTER_SANITIZE_STRING);
            $price_input = trim($_POST['price'] ?? '');
            $price = $price_input === '' ? '0.00' : $price_input;
            if ($price !== '0.00' && !preg_match('/^\d+(\.\d{1,2})?$/', $price)) {
                throw new Exception('Invalid price format.');
            }
            $price = number_format((float)$price, 2, '.', '');
            $order_multiple = intval($_POST['order_multiple'] ?? 1);
            $item_type = $_POST['item_type'] ?? 'general';
            $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
            if ($category === 'Other') {
                $category = filter_input(INPUT_POST, 'custom_category', FILTER_SANITIZE_STRING);
            }
            // Handle image upload if new
            $image_url = $_POST['existing_image'] ?? '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = 'product_images/';
                $target_file = $target_dir . basename($_FILES['image']['name']);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $image_url = $target_file;
                }
            }
            $stmt = $pdo->prepare("UPDATE catalog_items SET item_name = :name, description = :desc, sku = :sku, price = :price, image_url = :image, order_multiple = :multiple, item_type = :type, category = :cat WHERE id = :id");
            $stmt->execute([
                'name' => $item_name,
                'desc' => $description,
                'sku' => $sku,
                'price' => $price,
                'image' => $image_url,
                'multiple' => $order_multiple,
                'type' => $item_type,
                'cat' => $category,
                'id' => $item_id
            ]);
            echo json_encode(['success' => true, 'message' => 'Item updated.']);
        } elseif ($action === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM catalog_items WHERE id = :id");
            $stmt->execute(['id' => $item_id]);
            echo json_encode(['success' => true, 'message' => 'Item deleted.']);
        } elseif ($action === 'bulk_import') {
            if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] == 0) {
                $file = $_FILES['csv_file']['tmp_name'];
                if (($handle = fopen($file, "r")) !== FALSE) {
                    $header = fgetcsv($handle); // Skip header
                    while (($data = fgetcsv($handle)) !== FALSE) {
                        if (count($data) >= 8) {
                            $price_input = trim($data[3]);
                            $price = $price_input === '' ? '0.00' : $price_input;
                            if ($price !== '0.00' && !preg_match('/^\d+(\.\d{1,2})?$/', $price)) continue;
                            $price = number_format((float)$price, 2, '.', '');
                            $stmt = $pdo->prepare("INSERT INTO catalog_items (item_name, description, sku, price, image_url, order_multiple, item_type, category) VALUES (:name, :desc, :sku, :price, :image, :multiple, :type, :cat)");
                            $stmt->execute([
                                'name' => $data[0],
                                'desc' => $data[1],
                                'sku' => $data[2],
                                'price' => $price,
                                'image' => $data[4],
                                'multiple' => intval($data[5]),
                                'type' => $data[6],
                                'cat' => $data[7]
                            ]);
                        }
                    }
                    fclose($handle);
                    echo json_encode(['success' => true, 'message' => 'Bulk import complete.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error reading CSV.']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
            }
        } elseif ($action === 'fetch_details') {
            $stmt = $pdo->prepare("SELECT * FROM catalog_items WHERE id = :id");
            $stmt->execute(['id' => $item_id]);
            $item = $stmt->fetch();
            echo json_encode(['success' => true, 'item' => $item]);
        }
    } catch (PDOException $e) {
        error_log("Catalog action error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
// Fetch catalog items grouped by category
$stmt = $pdo->query("SELECT * FROM catalog_items ORDER BY category, item_name");
$items = $stmt->fetchAll();
$categories = [];
foreach ($items as $item) {
    $cat = $item['category'] ?: 'Uncategorized';
    $categories[$cat][] = $item;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Master Catalog</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>Master Catalog</h1>
        <div id="responseMessage"></div>
        <!-- Add Item Form -->
        <div class="card mb-3">
            <div class="card-body">
                <h3>Add Item</h3>
                <form id="addItemForm" enctype="multipart/form-data">
                    <label>Name:</label><input type="text" name="item_name" class="form-control" required><br>
                    <label>Description:</label><textarea name="description" class="form-control"></textarea><br>
                    <label>SKU:</label><input type="text" name="sku" class="form-control"><br>
                    <label>Price (optional):</label><input type="text" name="price" pattern="^$|^\d+(\.\d{1,2})?$" class="form-control" placeholder="e.g., 2999.00"><br>
                    <label>Image:</label><input type="file" name="image" class="form-control"><br>
                    <label>Order Multiple:</label><input type="number" name="order_multiple" value="1" class="form-control"><br>
                    <label>Type:</label>
                    <select name="item_type" class="form-control">
                        <option value="general">General</option>
                        <option value="paint">Paint</option>
                        <option value="propane">Propane</option>
                    </select><br>
                    <label>Category:</label>
                    <select name="category" id="add_category_select" class="form-control">
                        <?php foreach ($predefined_categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                        <option value="Other">Other</option>
                    </select>
                    <input type="text" name="custom_category" id="add_custom_category" class="form-control mt-2" style="display:none;" placeholder="Enter custom category"><br>
                    <button type="submit" class="btn btn-primary w-100">Add</button>
                </form>
            </div>
        </div>
        <!-- Bulk Import -->
        <div class="card mb-3">
            <div class="card-body">
                <h3>Bulk Import CSV</h3>
                <p>CSV format: item_name,description,sku,price,image_url,order_multiple,item_type,category. Leave price blank to omit.</p>
                <form id="bulkImportForm" enctype="multipart/form-data">
                    <input type="file" name="csv_file" accept=".csv" class="form-control mb-2" required>
                    <button type="submit" class="btn btn-secondary w-100">Import</button>
                </form>
            </div>
        </div>
        <!-- Catalog by Category -->
        <div class="accordion" id="catalogAccordion">
            <?php foreach ($categories as $cat => $cat_items): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#cat<?php echo md5($cat); ?>">
                            <?php echo htmlspecialchars($cat); ?> (<?php echo count($cat_items); ?> items)
                        </button>
                    </h2>
                    <div id="cat<?php echo md5($cat); ?>" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>SKU</th>
                                            <th>Price</th>
                                            <th>Multiple</th>
                                            <th>Type</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cat_items as $item): ?>
                                            <tr data-item-id="<?php echo $item['id']; ?>">
                                                <td><?php echo $item['id']; ?></td>
                                                <td><img src="<?php echo htmlspecialchars($item['image_url'] ?: 'images/placeholder.jpg'); ?>" style="width:50px;"></td>
                                                <td contenteditable="true" data-field="item_name"><?php echo $item['item_name']; ?></td>
                                                <td contenteditable="true" data-field="description"><?php echo $item['description']; ?></td>
                                                <td contenteditable="true" data-field="sku"><?php echo htmlspecialchars($item['sku']); ?></td>
                                                <td contenteditable="true" data-field="price"><?php echo $item['price']; ?></td>
                                                <td contenteditable="true" data-field="order_multiple"><?php echo $item['order_multiple']; ?></td>
                                                <td contenteditable="true" data-field="item_type"><?php echo htmlspecialchars($item['item_type']); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-primary edit-item" data-bs-toggle="modal" data-bs-target="#editItemModal">Full Edit</button>
                                                    <button class="btn btn-sm btn-danger delete-item">Delete</button>
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
        <div class="modal fade" id="editItemModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editItemForm" enctype="multipart/form-data">
                            <input type="hidden" name="item_id" id="edit_item_id">
                            <label>Name:</label><input type="text" name="item_name" id="edit_name" class="form-control"><br>
                            <label>Description:</label><textarea name="description" id="edit_desc" class="form-control"></textarea><br>
                            <label>SKU:</label><input type="text" name="sku" id="edit_sku" class="form-control"><br>
                            <label>Price (optional):</label><input type="text" name="price" id="edit_price" pattern="^$|^\d+(\.\d{1,2})?$" class="form-control" placeholder="e.g., 2999.00"><br>
                            <label>Current Image:</label><img id="edit_image_preview" style="max-width:100px;"><input type="hidden" name="existing_image" id="edit_existing_image"><br>
                            <label>New Image:</label><input type="file" name="image" class="form-control"><br>
                            <label>Order Multiple:</label><input type="number" name="order_multiple" id="edit_multiple" class="form-control"><br>
                            <label>Type:</label><select name="item_type" id="edit_type" class="form-control"><option value="general">General</option><option value="paint">Paint</option><option value="propane">Propane</option></select><br>
                            <label>Category:</label>
                            <select name="category" id="edit_category_select" class="form-control">
                                <?php foreach ($predefined_categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                                <?php endforeach; ?>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" name="custom_category" id="edit_custom_category" class="form-control mt-2" style="display:none;" placeholder="Enter custom category"><br>
                            <button type="submit" class="btn btn-primary w-100">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <a href="admin_dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a>
    </div>
    <script>
        $(document).ready(function() {
            // Inline Edit Auto-Save
            $('td[contenteditable="true"]').on('blur', function() {
                let $td = $(this);
                let itemId = $td.closest('tr').data('item-id');
                let field = $td.data('field');
                let value = $td.text().trim();
                if (field === 'price' && value !== '' && !/^\d+(\.\d{1,2})?$/.test(value)) {
                    alert('Invalid price format.');
                    $td.focus();
                    return;
                }
                $.post('admin_catalog.php', {
                    action: 'edit',
                    item_id: itemId,
                    [field]: value
                }, function(response) {
                    if (!response.success) alert('Error updating.');
                }, 'json');
            });
            // Category Other Toggle for Add
            $('#add_category_select').change(function() {
                $('#add_custom_category').toggle(this.value === 'Other');
            });
            // Category Other Toggle for Edit
            $('#edit_category_select').change(function() {
                $('#edit_custom_category').toggle(this.value === 'Other');
            });
            // Add Item
            $('#addItemForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append('action', 'add');
                $.ajax({
                    url: 'admin_catalog.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                        if (response.success) location.reload();
                    }
                });
            });
            // Bulk Import
            $('#bulkImportForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append('action', 'bulk_import');
                $.ajax({
                    url: 'admin_catalog.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                        if (response.success) location.reload();
                    }
                });
            });
            // Edit Modal Populate
            $('.edit-item').click(function() {
                let itemId = $(this).closest('tr').data('item-id');
                $.post('admin_catalog.php', { action: 'fetch_details', item_id: itemId }, function(response) {
                    if (response.success) {
                        let item = response.item;
                        $('#edit_item_id').val(item.id);
                        $('#edit_name').val(item.item_name);
                        $('#edit_desc').val(item.description);
                        $('#edit_sku').val(item.sku);
                        $('#edit_price').val(item.price);
                        $('#edit_image_preview').attr('src', item.image_url || 'images/placeholder.jpg');
                        $('#edit_existing_image').val(item.image_url);
                        $('#edit_multiple').val(item.order_multiple);
                        $('#edit_type').val(item.item_type);
                        let cat = item.category;
                        if (!$('#edit_category_select option[value="' + cat + '"]').length) {
                            cat = 'Other';
                            $('#edit_custom_category').val(item.category).show();
                        } else {
                            $('#edit_custom_category').hide();
                        }
                        $('#edit_category_select').val(cat);
                    }
                }, 'json');
            });
            $('#editItemForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                formData.append('action', 'edit');
                $.ajax({
                    url: 'admin_catalog.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                        if (response.success) {
                            $('#editItemModal').modal('hide');
                            location.reload();
                        }
                    }
                });
            });
            // Delete
            $('.delete-item').click(function() {
                let itemId = $(this).closest('tr').data('item-id');
                if (confirm('Delete item?')) {
                    $.post('admin_catalog.php', { action: 'delete', item_id: itemId }, function(response) {
                        if (response.success) location.reload();
                        else alert(response.message);
                    }, 'json');
                }
            });
            // Remember Accordion State
            $('.accordion-button').on('shown.bs.collapse hidden.bs.collapse', function () {
                let state = {};
                $('.accordion-button').each(function() {
                    let id = $(this).data('bs-target');
                    state[id] = $(id).hasClass('show');
                });
                localStorage.setItem('catalogAccordionState', JSON.stringify(state));
            });
            // Restore State on Load
            let savedState = localStorage.getItem('catalogAccordionState');
            if (savedState) {
                savedState = JSON.parse(savedState);
                for (let id in savedState) {
                    if (savedState[id]) {
                        $(id).addClass('show');
                        $('[data-bs-target="' + id + '"]').removeClass('collapsed');
                    } else {
                        $(id).removeClass('show');
                        $('[data-bs-target="' + id + '"]').addClass('collapsed');
                    }
                }
            } else {
                $('.accordion-collapse').removeClass('show');
                $('.accordion-button').addClass('collapsed');
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
