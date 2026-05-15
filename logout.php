<?php
/**
 * resupply - Logout Page
 * Updated for new folder structure (May 14, 2026)
 * Clean logout with session destroyed before any includes
 */

// Destroy session FIRST (before any includes)
session_start();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logged Out - Resupply Rocket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center shadow-sm">
                <div class="card-body p-5">
                    <h2 class="mb-4">You have been logged out</h2>
                    <p class="text-muted mb-4">Thank you for using Resupply Rocket!</p>
                    <a href="login.php" class="btn btn-primary btn-lg">Login Again</a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>