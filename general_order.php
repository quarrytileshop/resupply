<?php
// general_order.php – Modified March 11, 2025 23:15 PDT – Lines: 328
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Fetch user details
$stmt = $pdo->prepare("SELECT organization_id, is_propane, is_organization_admin FROM users WHERE id = :id");
$stmt->execute(['id' => $user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found. Please log in again.");
}

$organization_id = $user['organization_id'] ?? null;
$is_propane = $user['is_propane'];
$is_organization_admin = $user['is_organization_admin'];

// If propane-flagged, redirect to propane form
if ($is_propane) {
    header("Location: order.php");
    exit;
}

// Show message if no organization assigned
if (!$organization_id) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Error - No Organization</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container text-center py-5">
            <img src="icons/logo-192.png" alt="Logo" class="logo mb-4" style="max-width: 150px;">
            <div class="alert alert-warning">
                <h2 class="mb-4">Your account is not assigned to any organization yet</h2>
                <p class="lead">To place general orders, you need to be part of an organization.</p>
                <p>Please contact the platform admin (Russell) or ask an organization admin to invite you.</p>
                <hr>
                <p><strong>Admin contact:</strong> russellhb2b@gmail.com</p>
            </div>
            <a href="dashboard.php" class="btn btn-primary btn-lg">Back to Dashboard</a>
            <a href="logout.php" class="btn btn-outline-secondary mt-3">Logout</a>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
    <?php
    exit;
}

// Proceed with normal loading if organization_id exists
// Fetch shopping lists for this organization
$stmt = $pdo->prepare("SELECT * FROM shopping_lists WHERE organization_id = :organization_id ORDER BY name");
$stmt->execute(['organization_id' => $organization_id]);
$shopping_lists = $stmt->fetchAll();

// Fetch master catalog items (general only)
$stmt = $pdo->prepare("SELECT * FROM catalog_items WHERE item_type = 'general' ORDER BY category, item_name");
$stmt->execute();
$catalog_items = $stmt->fetchAll();

// Fetch overrides for this organization
$stmt = $pdo->prepare("SELECT catalog_item_id, custom_price FROM organization_item_overrides WHERE organization_id = :organization_id");
$stmt->execute(['organization_id' => $organization_id]);
$overrides = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Merge overrides into catalog
foreach ($catalog_items as &$item) {
    $item['effective_price'] = $overrides[$item['id']] ?? $item['price'];
}
unset($item);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New General Order</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>New General Order</h1>
        <div id="responseMessage"></div>

        <!-- Shopping List Selector -->
        <div class="card mb-3">
            <div class="card-body">
                <h3>Use Existing Shopping List</h3>
                <select id="shoppingListSelect" class="form-control mb-3">
                    <option value="">-- Select a List --</option>
                    <?php foreach ($shopping_lists as $list): ?>
                        <option value="<?php echo $list['id']; ?>"><?php echo htmlspecialchars($list['name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <button id="loadListBtn" class="btn btn-primary" disabled>Load List</button>
            </div>
        </div>

        <!-- Master Catalog -->
        <div class="card mb-3">
            <div class="card-body">
                <h3>Master Catalog</h3>
                <input type="text" id="catalogSearch" placeholder="Search by name or SKU" class="form-control mb-3">
                <div class="table-responsive">
                    <table class="table table-striped" id="catalogTable">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Multiple</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($catalog_items as $item): ?>
                                <tr data-item-id="<?php echo $item['id']; ?>" class="catalog-row">
                                    <td><img src="<?php echo htmlspecialchars($item['image_url'] ?: 'images/placeholder.jpg'); ?>" style="width:50px;"></td>
                                    <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['sku'] ?: 'N/A'); ?></td>
                                    <td>$<?php echo number_format($item['effective_price'], 2); ?></td>
                                    <td><?php echo $item['order_multiple']; ?></td>
                                    <td><?php echo htmlspecialchars($item['category'] ?: 'Uncategorized'); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-success add-to-order" data-id="<?php echo $item['id']; ?>">Add</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Current Order -->
        <div class="card mb-3">
            <div class="card-body">
                <h3>Current Order</h3>
                <div class="table-responsive">
                    <table class="table table-striped" id="orderTable">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <strong>Total: <span id="orderTotal">$0.00</span></strong>
                </div>
                <div class="mt-3">
                    <label>PO Number:</label><input type="text" id="poNumber" class="form-control w-50 d-inline-block"><br>
                    <label>Delivery Address:</label><textarea id="deliveryAddress" class="form-control"></textarea><br>
                    <label>Internal Notes:</label><textarea id="internalNotes" class="form-control"></textarea><br>
                    <button id="sendOrderBtn" class="btn btn-success w-100 mt-3">Send Order</button>
                    <button id="saveDraftBtn" class="btn btn-secondary w-100 mt-2">Save Draft</button>
                </div>
            </div>
        </div>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <script>
        let currentOrder = [];
        let catalogItems = <?php echo json_encode($catalog_items); ?>;

        $(document).ready(function() {
            // Load list button enable/disable
            $('#shoppingListSelect').change(function() {
                $('#loadListBtn').prop('disabled', !$(this).val());
            });

            // Load list (placeholder - add real fetch if needed)
            $('#loadListBtn').click(function() {
                let listId = $('#shoppingListSelect').val();
                if (listId) {
                    alert('Loading list ID ' + listId + ' - feature not fully implemented yet');
                }
            });

            // Catalog search filter
            $('#catalogSearch').on('input', function() {
                let query = $(this).val().toLowerCase();
                $('.catalog-row').each(function() {
                    let name = $(this).find('td:eq(1)').text().toLowerCase();
                    let sku = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(name.includes(query) || sku.includes(query));
                });
            });

            // Add to order
            $(document).on('click', '.add-to-order', function() {
                let itemId = $(this).data('id');
                let item = catalogItems.find(i => i.id == itemId);
                if (item) {
                    let existing = currentOrder.find(o => o.id == itemId);
                    if (existing) {
                        existing.quantity += parseInt(item.order_multiple);
                    } else {
                        currentOrder.push({ ...item, quantity: parseInt(item.order_multiple) });
                    }
                    renderOrder();
                }
            });

            // Render current order table
            function renderOrder() {
                let html = '';
                let total = 0;
                currentOrder.forEach(item => {
                    let subtotal = item.quantity * item.effective_price;
                    total += subtotal;
                    html += '<tr><td>' + item.item_name + '</td><td><input type="number" class="order-qty form-control w-50" data-id="' + item.id + '" value="' + item.quantity + '" min="0" step="' + item.order_multiple + '"></td><td>$' + item.effective_price.toFixed(2) + '</td><td>$' + subtotal.toFixed(2) + '</td><td><button class="btn btn-sm btn-danger remove-item" data-id="' + item.id + '">Remove</button></td></tr>';
                });
                $('#orderTable tbody').html(html);
                $('#orderTotal').text('$' + total.toFixed(2));
            }

            // Update quantity on change
            $(document).on('change', '.order-qty', function() {
                let itemId = $(this).data('id');
                let qty = parseInt($(this).val()) || 0;
                let item = currentOrder.find(o => o.id == itemId);
                if (item) {
                    item.quantity = qty;
                    renderOrder();
                }
            });

            // Remove item
            $(document).on('click', '.remove-item', function() {
                let itemId = $(this).data('id');
                currentOrder = currentOrder.filter(o => o.id != itemId);
                renderOrder();
            });

            // Send order (placeholder - implement submit_order.php)
            $('#sendOrderBtn').click(function() {
                if (currentOrder.length === 0) return alert('No items in order.');
                let po = $('#poNumber').val();
                let addr = $('#deliveryAddress').val();
                let notes = $('#internalNotes').val();
                alert('Order would be sent with PO: ' + po + '\nAddress: ' + addr + '\nNotes: ' + notes + '\nItems: ' + JSON.stringify(currentOrder));
                // Real implementation: $.post('submit_order.php', { ... });
            });

            // Save draft (placeholder)
            $('#saveDraftBtn').click(function() {
                alert('Draft saving not implemented yet.');
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
