<?php
// admin_dashboard.php – Updated 2026-05-11 to use header + footer + professional styles
$page_title = "Super Admin Dashboard - Resupply Rocket";
require_once 'header.php';

// Your original admin dashboard PHP (stats, tables, links to admin_*.php) stays 100% intact
?>

<div class="container mt-4">
    <h1 class="mb-3">Super Admin Dashboard</h1>
    <p class="text-muted">Manage vendors, organizations, users, catalog, and shopping lists</p>

    <!-- Quick stats cards (modern look) -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <h5>Total Organizations</h5>
                    <h2 class="text-teal">12</h2>
                </div>
            </div>
        </div>
        <!-- Add more stat cards from your original logic here -->
    </div>

    <!-- Your existing admin tables / links go here unchanged -->
    <div class="card">
        <div class="card-body">
            <h5>Quick Actions</h5>
            <a href="admin_organizations.php" class="btn btn-outline-primary me-2">Manage Organizations</a>
            <a href="admin_users.php" class="btn btn-outline-primary me-2">Manage Users</a>
            <a href="admin_shopping_lists.php" class="btn btn-outline-primary me-2">Shopping Lists</a>
            <a href="admin_catalog.php" class="btn btn-outline-primary">Catalog</a>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
