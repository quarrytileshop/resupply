<?php
// paint_order.php – Modified March 11, 2025 22:45 PDT – Lines: 312
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
$organization_id = $user['organization_id'] ?? null;
$is_propane = $user['is_propane'];
$is_organization_admin = $user['is_organization_admin'];

// If no organization, show message
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
                <p class="lead">To place paint orders, you need to be part of an organization.</p>
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

// Fetch paint items from catalog
$stmt = $pdo->prepare("SELECT * FROM catalog_items WHERE item_type = 'paint' ORDER BY item_name");
$stmt->execute();
$paint_items = $stmt->fetchAll();

// Fetch overrides for this organization
$stmt = $pdo->prepare("SELECT catalog_item_id, custom_price FROM organization_item_overrides WHERE organization_id = :organization_id");
$stmt->execute(['organization_id' => $organization_id]);
$overrides = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Merge overrides
foreach ($paint_items as &$item) {
    $item['effective_price'] = $overrides[$item['id']] ?? $item['price'];
}
unset($item);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Paint Order</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Logo" class="logo">
        <h1>New Paint Order</h1>
        <div id="responseMessage"></div>

        <!-- Paint Catalog -->
        <div class="card mb-3">
            <div class="card-body">
                <h3>Paint Catalog</h3>
                <input type="text" id="paintSearch" placeholder="Search by name or brand" class="form-control mb-3">
                <div class="table-responsive">
                    <table class="table table-striped" id="paintTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Brand</th>
                                <th>Price (1 Gal)</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paint_items as $item): ?>
                                <tr data-item-id="<?php echo $item['id']; ?>" class="paint-row">
                                    <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($item['paint_brand'] ?? 'N/A'); ?></td>
                                    <td>$<?php echo number_format($item['effective_price'], 2); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-success add-paint" data-id="<?php echo $item['id']; ?>">Add Paint</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Current Paint Order -->
        <div class="card mb-3">
            <div class="card-body">
                <h3>Current Paint Order</h3>
                <div class="table-responsive">
                    <table class="table table-striped" id="paintOrderTable">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Size</th>
                                <th>Type</th>
                                <th>Sheen</th>
                                <th>Brand</th>
                                <th>Color</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <strong>Total: <span id="paintTotal">$0.00</span></strong>
                </div>
                <div class="mt-3">
                    <label>PO Number:</label><input type="text" id="poNumber" class="form-control w-50 d-inline-block"><br>
                    <label>Delivery Address:</label><textarea id="deliveryAddress" class="form-control"></textarea><br>
                    <label>Internal Notes / Special Instructions:</label><textarea id="internalNotes" class="form-control"></textarea><br>
                    <button id="sendPaintOrderBtn" class="btn btn-success w-100 mt-3">Send Paint Order</button>
                    <button id="saveDraftBtn" class="btn btn-secondary w-100 mt-2">Save Draft</button>
                </div>
            </div>
        </div>
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
    <script>
        let currentPaintOrder = [];
        let paintItems = <?php echo json_encode($paint_items); ?>;

        $(document).ready(function() {
            // Paint search filter
            $('#paintSearch').on('input', function() {
                let query = $(this).val().toLowerCase();
                $('.paint-row').each(function() {
                    let name = $(this).find('td:eq(0)').text().toLowerCase();
                    let brand = $(this).find('td:eq(1)').text().toLowerCase();
                    $(this).toggle(name.includes(query) || brand.includes(query));
                });
            });

            // Add paint to order
            $(document).on('click', '.add-paint', function() {
                let itemId = $(this).data('id');
                let item = paintItems.find(i => i.id == itemId);
                if (item) {
                    currentPaintOrder.push({
                        id: item.id,
                        name: item.item_name,
                        size: '1 Gallon', // default
                        type: 'Interior', // default
                        sheen: 'Flat', // default
                        brand: item.paint_brand || 'N/A',
                        color: '',
                        quantity: 1,
                        price: item.effective_price
                    });
                    renderPaintOrder();
                }
            });

            // Render current paint order
            function renderPaintOrder() {
                let html = '';
                let total = 0;
                currentPaintOrder.forEach((item, index) => {
                    let subtotal = item.quantity * item.price;
                    total += subtotal;
                    html += '<tr data-index="' + index + '">';
                    html += '<td>' + item.name + '</td>';
                    html += '<td><select class="paint-size form-control"><option>1 Gallon</option><option>5 Gallon</option><option>Quart</option><option>Sample</option></select></td>';
                    html += '<td><select class="paint-type form-control"><option>Interior</option><option>Exterior</option></select></td>';
                    html += '<td><select class="paint-sheen form-control"><option>Flat</option><option>Matte</option><option>Eggshell</option><option>Pearl</option><option>Satin</option><option>Soft Gloss</option><option>Semi Gloss</option><option>Hi Gloss</option></select></td>';
                    html += '<td>' + item.brand + '</td>';
                    html += '<td><input type="text" class="paint-color form-control" value="' + item.color + '" placeholder="e.g., Chantilly Lace"></td>';
                    html += '<td><input type="number" class="paint-qty form-control" value="' + item.quantity + '" min="1"></td>';
                    html += '<td>$' + item.price.toFixed(2) + '</td>';
                    html += '<td>$' + subtotal.toFixed(2) + '</td>';
                    html += '<td><button class="btn btn-sm btn-danger remove-paint" data-index="' + index + '">Remove</button></td>';
                    html += '</tr>';
                });
                $('#paintOrderTable tbody').html(html);
                $('#paintTotal').text('$' + total.toFixed(2));
            }

            // Update paint details on change
            $(document).on('change input', '.paint-size, .paint-type, .paint-sheen, .paint-color, .paint-qty', function() {
                let index = $(this).closest('tr').data('index');
                let item = currentPaintOrder[index];
                if (item) {
                    if ($(this).hasClass('paint-size')) item.size = $(this).val();
                    if ($(this).hasClass('paint-type')) item.type = $(this).val();
                    if ($(this).hasClass('paint-sheen')) item.sheen = $(this).val();
                    if ($(this).hasClass('paint-color')) item.color = $(this).val();
                    if ($(this).hasClass('paint-qty')) item.quantity = parseInt($(this).val()) || 1;
                    renderPaintOrder(); // Re-render to update subtotal
                }
            });

            // Remove paint item
            $(document).on('click', '.remove-paint', function() {
                let index = $(this).data('index');
                currentPaintOrder.splice(index, 1);
                renderPaintOrder();
            });

            // Send paint order
            $('#sendPaintOrderBtn').click(function() {
                if (currentPaintOrder.length === 0) return alert('No paint items in order.');
                let po = $('#poNumber').val();
                let addr = $('#deliveryAddress').val();
                let notes = $('#internalNotes').val();
                $.post('submit_paint_order.php', { 
                    items: JSON.stringify(currentPaintOrder), 
                    po_number: po, 
                    delivery_address: addr, 
                    internal_notes: notes
                }, function(response) {
                    if (response.success) {
                        alert('Paint order sent!');
                        currentPaintOrder = [];
                        renderPaintOrder();
                    } else {
                        alert('Error: ' + response.message);
                    }
                }, 'json');
            });

            // Save draft (placeholder)
            $('#saveDraftBtn').click(function() {
                alert('Paint draft saving not implemented yet.');
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
