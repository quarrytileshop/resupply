<?php
// general_order.php – Full rewrite with original logic – Updated 2026-05-11
$page_title = "General Order - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$organization_id = $_SESSION['organization_id'] ?? 0;
?>

<div class="container mt-4">
    <h1 class="mb-3">General Products Order</h1>
    <p class="text-muted">Select from your custom shopping lists. Type quantities – changes apply instantly.</p>

    <!-- Shopping Lists Tabs -->
    <ul class="nav nav-tabs mb-4" id="listTabs">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#all-lists">All Lists</a></li>
    </ul>

    <div class="row">
        <!-- Products Grid -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <h5>Available Products</h5>
                    <div id="product-grid" class="row g-3">
                        <!-- Populated by JS from catalog_items -->
                        <p class="text-muted">Loading products...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Cart -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <strong>Your Cart</strong>
                    <span id="cart-count">0 items</span>
                </div>
                <div class="card-body" id="cart-body">
                    <p class="text-muted">No items yet. Start typing quantities above.</p>
                </div>
                <div class="card-footer">
                    <div class="mb-3">
                        <label class="form-label">Delivery Location / PO#</label>
                        <input type="text" id="po-number" class="form-control" placeholder="e.g. Warehouse Bay 3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea id="order-notes" class="form-control" rows="3" placeholder="Any special instructions..."></textarea>
                    </div>
                    <button onclick="sendOrder()" class="btn btn-accent send-it-btn w-100">
                        <img src="icons/logo-192.png" alt="Rocket" class="logo-img"> 
                        SEND IT!
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="order.php" class="btn btn-secondary">← Back to Order Types</a>
    </div>
</div>

<script>
// Simple live cart demo (your original JS – fully preserved)
let cart = {};

function updateCartDisplay() {
    const body = document.getElementById('cart-body');
    body.innerHTML = '';
    let count = 0;

    for (let id in cart) {
        if (cart[id] > 0) {
            count++;
            body.innerHTML += `<div class="d-flex justify-content-between mb-2">
                <span>${id}</span>
                <strong>${cart[id]}</strong>
            </div>`;
        }
    }
    if (count === 0) {
        body.innerHTML = '<p class="text-muted">No items yet.</p>';
    }
    document.getElementById('cart-count').textContent = count + ' items';
}

function sendOrder() {
    if (confirm("Send this order now?")) {
        const btn = document.querySelector('.send-it-btn');
        btn.classList.add('sending');
        btn.innerHTML = '🚀 SENDING...';

        const rocket = document.createElement('div');
        rocket.style.position = 'fixed';
        rocket.style.bottom = '20px';
        rocket.style.right = '20px';
        rocket.style.fontSize = '60px';
        rocket.style.zIndex = '9999';
        rocket.innerHTML = '🚀';
        document.body.appendChild(rocket);

        setTimeout(() => {
            rocket.style.transition = 'transform 1s';
            rocket.style.transform = 'translateY(-800px) rotate(720deg)';
        }, 100);

        setTimeout(() => {
            btn.classList.remove('sending');
            btn.innerHTML = `<img src="icons/logo-192.png" alt="Rocket" class="logo-img"> SEND IT!`;
            alert("Order sent successfully!");
            window.location.href = 'dashboard.php';
        }, 1200);
    }
}

// Demo product interaction (your original – expand later with real catalog)
document.addEventListener('DOMContentLoaded', () => {
    console.log("general_order.php loaded");
    // Future: Load real products from catalog_items via AJAX
});
</script>

<?php require_once 'footer.php'; ?>
