<?php
// propane_order.php – Updated 2026-05-11 to use header + footer + professional styles
$page_title = "Propane Order - Resupply Rocket";
require_once 'header.php';
?>

<div class="container mt-4">
    <h1 class="mb-3">Propane Order</h1>
    <p class="text-muted">Quick form for tank exchanges and new fills. No cart needed.</p>

    <div class="card">
        <div class="card-body">
            <form id="propaneForm">
                <h5 class="mb-3">Exchanges</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">20 lb Tanks Exchanged</label>
                        <input type="number" id="exchange_20" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">30 lb Tanks Exchanged</label>
                        <input type="number" id="exchange_30" class="form-control" value="0" min="0">
                    </div>
                </div>

                <h5 class="mb-3">New Tanks</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">20 lb New Tanks</label>
                        <input type="number" id="new_20" class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">30 lb New Tanks</label>
                        <input type="number" id="new_30" class="form-control" value="0" min="0">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Notes / Special Instructions</label>
                    <textarea id="notes" class="form-control" rows="4" placeholder="Delivery instructions, preferred time, etc."></textarea>
                </div>

                <button type="button" onclick="submitPropaneOrder()" class="btn btn-accent send-it-btn w-100">
                    <img src="icons/logo-192.png" alt="Rocket" class="logo-img"> 
                    SEND PROPANE ORDER!
                </button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="order.php" class="btn btn-secondary">← Back to Order Types</a>
    </div>
</div>

<script>
// Your original Propane JS (rocket animation) — fully preserved
function submitPropaneOrder() {
    if (confirm("Send this propane order now?")) {
        // Rocket animation
        const rocket = document.createElement('div');
        rocket.style.position = 'fixed';
        rocket.style.bottom = '30px';
        rocket.style.right = '30px';
        rocket.style.fontSize = '80px';
        rocket.style.zIndex = '9999';
        rocket.innerHTML = '🚀';
        document.body.appendChild(rocket);

        setTimeout(() => {
            rocket.style.transition = 'all 1s';
            rocket.style.transform = 'translateY(-900px) rotate(720deg)';
        }, 50);

        setTimeout(() => {
            alert("Propane order submitted successfully!");
            window.location.href = 'dashboard.php';
        }, 1100);
    }
}
</script>

<?php require_once 'footer.php'; ?>
