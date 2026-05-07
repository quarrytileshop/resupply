<?php

// send_po_email.php

require_once 'config.php';
require_once 'email_functions.php';

// Assume this file is called with order_id or form data; for now, simulate or adjust as needed
// Example: if called from order.php or other, pass $order_id via GET/POST
$order_id = $_POST['order_id'] ?? $_GET['order_id'] ?? null;
if (!$order_id) {
    die("No order ID provided.");
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch order details
    $stmt = $pdo->prepare("SELECT user_id, fulfillment_type, po_number, internal_notes FROM orders WHERE id = :id");
    $stmt->execute(['id' => $order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order) {
        die("Order not found.");
    }

    $user_id = $order['user_id'];
    $fulfillment_type = $order['fulfillment_type'] ?? 'delivery';
    $po_number = $order['po_number'] ?? '';
    $order_notes = $order['internal_notes'] ?? '';

    // Fetch user
    $stmt = $pdo->prepare("SELECT username, email, business_name, account_number, phone_number, delivery_address FROM users WHERE id = :id");
    $stmt->execute(['id' => $user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch order items
    $stmt = $pdo->prepare("SELECT ci.item_name as name, ci.description, ci.price, oi.quantity FROM order_items oi JOIN catalog_items ci ON oi.catalog_item_id = ci.id WHERE oi.order_id = :order_id");
    $stmt->execute(['order_id' => $order_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Determine if prices available
    $has_prices = false;
    foreach ($items as $item) {
        if ($item['price'] > 0) {
            $has_prices = true;
            break;
        }
    }

    // Build PO HTML
    $po_html = '<html><body>';
    $po_html .= '<h1>Purchase Order #' . $order_id . '</h1>';
    $po_html .= '<p>Business Name: ' . htmlspecialchars($user['business_name']) . '</p>';
    $po_html .= '<p>Account Number: ' . htmlspecialchars($user['account_number']) . '</p>';
    $po_html .= '<p>Phone: ' . htmlspecialchars($user['phone_number']) . '</p>';
    $po_html .= '<p>Email: ' . htmlspecialchars($user['email']) . '</p>';
    $po_html .= '<p>Delivery Address: ' . htmlspecialchars($user['delivery_address']) . '</p>';
    $po_html .= '<p>Fulfillment Type: ' . ucfirst($fulfillment_type) . '</p>';
    $po_html .= '<h2>Order Items</h2>';
    $po_html .= '<table border="1" style="border-collapse: collapse; width: 100%;">';
    $po_html .= '<tr style="background-color: #007bff; color: white;">';
    $po_html .= '<th>Item Name</th><th>Description</th><th>Quantity</th>';
    if ($has_prices) $po_html .= '<th>Price</th><th>Subtotal</th>';
    $po_html .= '</tr>';
    $total_quantity = 0;
    $total_price = 0.0;
    foreach ($items as $item) {
        $quantity = $item['quantity'];
        $price = $item['price'];
        $subtotal = $quantity * $price;
        $total_quantity += $quantity;
        $total_price += $subtotal;
        $po_html .= '<tr>';
        $po_html .= '<td>' . htmlspecialchars($item['name']) . '</td>';
        $po_html .= '<td>' . htmlspecialchars($item['description']) . '</td>';
        $po_html .= '<td>' . $quantity . '</td>';
        if ($has_prices) {
            $po_html .= '<td>$' . number_format($price, 2) . '</td>';
            $po_html .= '<td>$' . number_format($subtotal, 2) . '</td>';
        }
        $po_html .= '</tr>';
    }
    $po_html .= '<tr><td colspan="2"><strong>Total Quantity: ' . $total_quantity . '</strong></td>';
    if ($has_prices) $po_html .= '<td colspan="3"><strong>Total: $' . number_format($total_price, 2) . '</strong></td>';
    else $po_html .= '<td></td>';
    $po_html .= '</tr></table>';
    $po_html .= '<h2>Notes</h2><p>' . (empty($order_notes) ? 'No notes provided.' : htmlspecialchars($order_notes)) . '</p>';
    $po_html .= '<p>If you have questions, please text the order desk at 925 320 3050.</p>';
    $po_html .= '</body></html>';

    // Plain text
    $plain_body = "Purchase Order #$order_id\nBusiness: {$user['business_name']}\nAccount: {$user['account_number']}\nPhone: {$user['phone_number']}\nEmail: {$user['email']}\nDelivery: {$user['delivery_address']}\nFulfillment: " . ucfirst($fulfillment_type) . "\n\nItems:\n";
    foreach ($items as $item) {
        $plain_body .= "{$item['name']}: {$item['description']}, Qty: {$item['quantity']}";
        if ($has_prices) $plain_body .= ", Price: $" . number_format($item['price'], 2) . ", Subtotal: $" . number_format($item['quantity'] * $item['price'], 2);
        $plain_body .= "\n";
    }
    $plain_body .= "\nTotal Qty: $total_quantity";
    if ($has_prices) $plain_body .= "\nTotal: $" . number_format($total_price, 2);
    $plain_body .= "\nNotes: " . (empty($order_notes) ? 'None' : $order_notes) . "\nText 925 320 3050 for questions.";

    // Send
    $to = $user['email'] . ',russellhb2b@gmail.com';
    if (send_email($to, "Purchase Order #$order_id from {$user['business_name']}", $po_html, $plain_body)) {
        echo "PO sent successfully.";
    } else {
        echo "Failed to send PO.";
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo "Database error.";
}
?>



const CACHE_NAME = 'resupply-rocket-cache-v1';
const STATIC_ASSETS = [
  '/',
  '/login.php',
  '/dashboard.php',
  '/css/styles.css',
  '/manifest.json',
  '/icons/logo-192.png',
  '/icons/logo-512.png',
  // Add more static files as needed (e.g., '/js/custom.js' if any)
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(STATIC_ASSETS);
      })
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    Promise.all([
      self.clients.claim(),
      caches.keys().then(keys => {
        return Promise.all(
          keys.filter(key => key !== CACHE_NAME)
            .map(key => caches.delete(key))
        );
      })
    ])
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(cachedResponse => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(event.request)
          .then(networkResponse => {
            if (event.request.method === 'GET' && networkResponse.ok) {
              const clone = networkResponse.clone();
              caches.open(CACHE_NAME)
                .then(cache => cache.put(event.request, clone));
            }
            return networkResponse;
          });
      })
      .catch(() => {
        // Offline fallback: Return custom offline page if HTML request
        if (event.request.headers.get('accept').includes('text/html')) {
          return new Response('<h1>Offline</h1><p>Check your connection and try again.</p>', {
            headers: { 'Content-Type': 'text/html' }
          });
        }
      })
  );
});
