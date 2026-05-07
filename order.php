<?php
// order.php - Modification Date: August 25, 2025, 12:00 PM - Total Lines: 257
require_once 'config.php';
require_once 'email_functions.php';
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');
error_log("Accessing order.php, HTTP_HOST: " . $_SERVER['HTTP_HOST'] . ", User-Agent: " . $_SERVER['HTTP_USER_AGENT']);
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
// Check if impersonating
$is_impersonating = isset($_SESSION['impersonating']) && $_SESSION['impersonating'];
$impersonated_username = '';
if ($is_impersonating) {
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $imp_user = $stmt->fetch();
    $impersonated_username = $imp_user['username'] ?? 'Unknown';
}
try {
    $stmt = $pdo->prepare("SELECT username, email, delivery_address, access_notes, business_name, account_number, phone_number FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch();
    if (!$user) {
        die("User not found.");
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}
// Handle AJAX form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajax_submit'])) {
    header('Content-Type: application/json');
    $exchanges = intval($_POST['exchanges'] ?? 0);
    $new_tanks = intval($_POST['new_tanks'] ?? 0);
    $notes = $_POST['notes'] ?? '';
    if ($exchanges > 0 || $new_tanks > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, status, fulfillment_type, internal_notes) VALUES (:user_id, 'sent', 'delivery', :notes)");
            $stmt->execute(['user_id' => $user_id, 'notes' => $notes]);
            $order_id = $pdo->lastInsertId();
            if ($exchanges > 0) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, catalog_item_id, quantity) VALUES (:order_id, 110, :quantity)");
                $stmt->execute(['order_id' => $order_id, 'quantity' => $exchanges]);
            }
            if ($new_tanks > 0) {
                $stmt = $pdo->prepare("INSERT INTO order_items (order_id, catalog_item_id, quantity) VALUES (:order_id, 111, :quantity)");
                $stmt->execute(['order_id' => $order_id, 'quantity' => $new_tanks]);
            }
            $items = [];
            $all_prices_zero = true;
            if ($exchanges > 0) {
                $stmt = $pdo->prepare("SELECT item_name, price, image_url, sku FROM catalog_items WHERE id = 110");
                $stmt->execute();
                $item = $stmt->fetch();
                $item['quantity'] = $exchanges;
                $items[] = $item;
                if ($item['price'] > 0) $all_prices_zero = false;
            }
            if ($new_tanks > 0) {
                $stmt = $pdo->prepare("SELECT item_name, price, image_url, sku FROM catalog_items WHERE id = 111");
                $stmt->execute();
                $item = $stmt->fetch();
                $item['quantity'] = $new_tanks;
                $items[] = $item;
                if ($item['price'] > 0) $all_prices_zero = false;
            }
            $po_html = '<html><body style="font-family: Arial, Helvetica, sans-serif; color: #333; margin: 0; padding: 0;">';
            $po_html .= '<div style="max-width: 800px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); background-color: #fff;">';
            $po_html .= '<img src="https://' . $_SERVER['HTTP_HOST'] . '/icons/logo-192.png" alt="Resupply Rocket Logo" style="max-width: 150px; display: block; margin: 0 auto 20px;">';
            $po_html .= '<h2 style="text-align: center; color: #007bff; margin-bottom: 20px;">Propane Order PO #' . $order_id . '</h2>';
            $po_html .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">';
            $po_html .= '<tr style="background-color: #f8f9fa;"><td style="width: 50%; padding: 10px; border: 1px solid #ddd; vertical-align: top;"><strong>Customer Name:</strong> ' . htmlspecialchars($user['username']) . '</td>';
            $po_html .= '<td style="width: 50%; padding: 10px; border: 1px solid #ddd; vertical-align: top;"><strong>Business Name:</strong> ' . htmlspecialchars($user['business_name']) . '</td></tr>';
            $po_html .= '<tr><td style="width: 50%; padding: 10px; border: 1px solid #ddd; vertical-align: top;"><strong>Account Number:</strong> ' . htmlspecialchars($user['account_number']) . '</td>';
            $po_html .= '<td style="width: 50%; padding: 10px; border: 1px solid #ddd; vertical-align: top;"><strong>Phone:</strong> ' . htmlspecialchars($user['phone_number']) . '</td></tr>';
            $po_html .= '<tr style="background-color: #f8f9fa;"><td style="width: 50%; padding: 10px; border: 1px solid #ddd; vertical-align: top;"><strong>Email:</strong> ' . htmlspecialchars($user['email']) . '</td>';
            $po_html .= '<td style="width: 50%; padding: 10px; border: 1px solid #ddd; vertical-align: top;"><strong>Delivery Address:</strong> ' . htmlspecialchars($user['delivery_address']) . '</td></tr>';
            $po_html .= '<tr><td style="width: 50%; padding: 10px; border: 1px solid #ddd; vertical-align: top;"><strong>Fulfillment Type:</strong> Delivery</td>';
            $po_html .= '<td style="width: 50%; padding: 10px; border: 1px solid #ddd; vertical-align: top;"><strong>PO Number:</strong> N/A</td></tr>';
            $po_html .= '</table>';
            $po_html .= '<h3 style="color: #007bff; margin: 20px 0 10px;">Order Items</h3>';
            $po_html .= '<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px;">';
            $po_html .= '<thead><tr style="background-color: #007bff; color: white;">';
            $po_html .= '<th style="width: 15%; padding: 10px; border: 1px solid #ddd; text-align: center;">Image</th>';
            $po_html .= '<th style="width: 20%; padding: 10px; border: 1px solid #ddd; text-align: left; white-space: nowrap;">SKU</th>';
            $po_html .= '<th style="width: 35%; padding: 10px; border: 1px solid #ddd; text-align: left; white-space: nowrap;">Item Name</th>';
            $po_html .= '<th style="width: 15%; padding: 10px; border: 1px solid #ddd; text-align: right;">Quantity</th>';
            if (!$all_prices_zero) {
                $po_html .= '<th style="width: 15%; padding: 10px; border: 1px solid #ddd; text-align: right;">Price</th>';
                $po_html .= '<th style="width: 15%; padding: 10px; border: 1px solid #ddd; text-align: right;">Subtotal</th>';
            }
            $po_html .= '</tr></thead><tbody>';
            foreach ($items as $index => $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $background_color = ($index % 2 == 0) ? '#f8f9fa' : '#fff';
                $po_html .= '<tr style="background-color: ' . $background_color . ';">';
                $po_html .= '<td style="width: 15%; padding: 10px; border: 1px solid #ddd; text-align: center; vertical-align: middle;">';
                $po_html .= (isset($item['image_url']) && $item['image_url'] && file_exists(__DIR__ . '/' . $item['image_url']) ?
                    '<img src="https://' . $_SERVER['HTTP_HOST'] . '/' . $item['image_url'] . '" style="max-width: 50px; height: auto;" alt="' . htmlspecialchars($item['item_name']) . '">' :
                    '<img src="https://' . $_SERVER['HTTP_HOST'] . '/images/placeholder.jpg" style="max-width: 50px; height: auto;" alt="No Image">') . '</td>';
                $po_html .= '<td style="width: 20%; padding: 10px; border: 1px solid #ddd; text-align: left; white-space: nowrap;">' . htmlspecialchars($item['sku'] ?? 'N/A') . '</td>';
                $po_html .= '<td style="width: 35%; padding: 10px; border: 1px solid #ddd; text-align: left; white-space: nowrap;">' . htmlspecialchars($item['item_name']) . '</td>';
                $po_html .= '<td style="width: 15%; padding: 10px; border: 1px solid #ddd; text-align: right; vertical-align: middle;">' . $item['quantity'] . '</td>';
                if (!$all_prices_zero) {
                    $po_html .= '<td style="width: 15%; padding: 10px; border: 1px solid #ddd; text-align: right; vertical-align: middle;">$' . number_format($item['price'], 2) . '</td>';
                    $po_html .= '<td style="width: 15%; padding: 10px; border: 1px solid #ddd; text-align: right; vertical-align: middle;">$' . number_format($subtotal, 2) . '</td>';
                }
                $po_html .= '</tr>';
            }
            $po_html .= '</tbody></table>';
            $po_html .= '<h3 style="color: #007bff; margin: 20px 0 10px;">Notes</h3>';
            $po_html .= '<p style="border: 1px solid #ddd; padding: 10px; border-radius: 5px; background-color: #f8f9fa; font-size: 14px;">' . (empty($notes) ? 'No notes provided.' : htmlspecialchars($notes)) . '</p>';
            $po_html .= '</div></body></html>';
            $plain_body = "Propane Order PO #$order_id\nCustomer: " . htmlspecialchars($user['username']) . "\nBusiness: " . htmlspecialchars($user['business_name']) . "\nAccount: " . htmlspecialchars($user['account_number']) . "\nPhone: " . htmlspecialchars($user['phone_number']) . "\nEmail: " . htmlspecialchars($user['email']) . "\nDelivery Address: " . htmlspecialchars($user['delivery_address']) . "\nFulfillment Type: Delivery\nPO Number: N/A\n\nItems:\n";
            foreach ($items as $item) {
                $subtotal = $item['price'] * $item['quantity'];
                $plain_body .= "SKU: " . htmlspecialchars($item['sku'] ?? 'N/A') . ", Item Name: " . htmlspecialchars($item['item_name']) . ", Quantity: " . $item['quantity'];
                if (!$all_prices_zero) {
                    $plain_body .= ", Price: $" . number_format($item['price'], 2) . ", Subtotal: $" . number_format($subtotal, 2);
                }
                $plain_body .= "\n";
            }
            $plain_body .= "\nNotes: " . (empty($notes) ? 'No notes provided.' : htmlspecialchars($notes));
            $to = $user['email'] . ',russellhb2b@gmail.com'; // Testing mode, no vendor
            // $to = $user['email'] . ',russellhb2b@gmail.com,propane@supplier.com'; // Restore for production
            if (send_email($to, 'Propane Order PO #' . $order_id, $po_html, $plain_body)) {
                echo json_encode(['success' => true, 'message' => 'Order submitted successfully! Email sent.']);
                do_action('after_order_submit', $order_id);
            } else {
                echo json_encode(['success' => true, 'message' => 'Order submitted successfully, but email sending failed. Check spam folder or contact support.']);
            }
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error submitting order: Database error.']);
        }
    }
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Propane Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <img src="icons/logo-192.png" alt="Resupply Rocket Logo" class="logo">
        <h1>Propane Order</h1>
        <?php if ($is_impersonating): ?>
            <div class="alert alert-warning">
                Impersonating <?php echo htmlspecialchars($impersonated_username); ?> - <a href="exit_impersonate.php">Exit</a>
            </div>
        <?php endif; ?>
        <div id="responseMessage"></div>
        <form id="orderForm" method="post">
            <label>Number of Propane Exchanges:</label>
            <input type="text" inputmode="numeric" pattern="[0-9]*" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" name="exchanges" value="0" class="form-control">
            <label>Number of New Tanks:</label>
            <input type="text" inputmode="numeric" pattern="[0-9]*" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" name="new_tanks" value="0" class="form-control">
            <label>Notes:</label>
            <textarea name="notes" class="form-control"></textarea>
            <button type="submit" id="submitBtn" class="btn btn-primary send-it-btn mt-4 mx-auto d-block"> Send It! <img src="icons/logo-192.png" alt="Logo" class="logo-img"></button>
        </form>
        <div class="mt-3">
            <a href="dashboard.php">Dashboard</a> | <a href="general_order.php">General Order</a> | <a href="paint_order.php">Paint Order</a> | <a href="checkbox_create.php">Checkbox List</a> | <a href="logout.php">Logout</a>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(document).on('focus', '[name="exchanges"], [name="new_tanks"]', function() {
                if (this.value === '0') this.value = '';
                this.select();
            });
            $(document).on('blur', '[name="exchanges"], [name="new_tanks"]', function() {
                if (this.value === '') this.value = '0';
            });
            $('#orderForm').submit(function(e) {
                e.preventDefault();
                var $button = $('#submitBtn');
                var $logo = $button.find('.logo-img');
                $logo.addClass('sending');
                $button.prop('disabled', true);
                $.ajax({
                    url: 'order.php',
                    type: 'POST',
                    data: $(this).serialize() + '&ajax_submit=1',
                    dataType: 'json',
                    timeout: 15000,
                    success: function(response) {
                        $logo.removeClass('sending');
                        $button.prop('disabled', false);
                        $('#responseMessage').html('<p class="' + (response.success ? 'success' : 'error') + '">' + response.message + '</p>');
                        if (response.success) {
                            $('#orderForm')[0].reset();
                            window.scrollTo(0, 0);
                            setTimeout(() => location.reload(true), 4000);
                        }
                    },
                    error: function(xhr, status, error) {
                        $logo.removeClass('sending');
                        $button.prop('disabled', false);
                        $('#responseMessage').html('<p class="error">Error submitting order: Server timeout or error. Please try again.</p>');
                        window.scrollTo(0, 0);
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
