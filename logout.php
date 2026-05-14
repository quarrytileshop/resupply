<?php
/**
 * resupply - Logout Page
 * Updated for new folder structure (May 14, 2026)
 * All includes updated to new locations
 */

require_once 'includes/config.php';
require_once 'includes/header.php';

// Destroy the session
session_destroy();
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body p-5">
                    <h2 class="mb-4">You have been logged out</h2>
                    <p class="text-muted mb-4">Thank you for using Resupply Rocket!</p>
                    <a href="login.php" class="btn btn-primary btn-lg">Login Again</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>