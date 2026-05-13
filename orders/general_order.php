<?php
// general_order.php – Added usage logging after order is sent – 2026-05-12
$page_title = "General Order - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$organization_id = $_SESSION['organization_id'] ?? 0;
$vendor_id       = $_SESSION['vendor_id'] ?? 0;
?>

<div class="container mt-4">
    <h1 class="mb-3">General Products Order</h1>
    <p class="text-muted">Choose a shopping list created by your vendor or browse all products.</p>

    <!-- Your existing shopping lists and product grid go here (unchanged) -->

    <div class="mt-4 text-end">
        <button onclick="sendOrder()" class="btn btn-accent send-it-btn">
            <img src="icons/logo-192.png" alt="Rocket" class="logo-img"> 
            SEND IT!
        </button>
    </div>
</div>

<script>
// Your existing Send It! logic + usage logging
function sendOrder() {
    const btn = document.querySelector('.send-it-btn');
    btn.classList.add('sending');
    btn.innerHTML = '🚀 SENDING...';

    // Log usage (billing still switched off)
    fetch('record_usage.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'type=order'
    });

    // Your original rocket animation and order processing...
    setTimeout(() => {
        btn.classList.remove('sending');
        btn.innerHTML = `<img src="icons/logo-192.png" alt="Rocket" class="logo-img"> SEND IT!`;
        alert("Order sent successfully!");
        window.location.href = 'dashboard.php';
    }, 1500);
}
</script>

<?php require_once 'footer.php'; ?>
