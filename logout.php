<?php
// logout.php – Full rewrite – Updated 2026-05-11
require_once 'header.php';

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

<?php require_once 'footer.php'; ?>
