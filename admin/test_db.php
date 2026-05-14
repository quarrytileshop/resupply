<?php
/**
 * resupply - Test Database Connection (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Test Database - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$status = "Unknown";
$details = "";

try {
    // Simple connection test using the PDO from config.php
    $pdo->query("SELECT 1");
    $status = "✅ Connected Successfully";
    $details = "Database connection is working. PDO is ready.";

    // Optional: show basic info
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $details .= "<br><strong>Tables found:</strong> " . count($tables);
} catch (Exception $e) {
    $status = "❌ Connection Failed";
    $details = "Error: " . htmlspecialchars($e->getMessage());
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Database Connection Test</h1>
    <p class="text-muted">Quick diagnostic tool for super-admins.</p>

    <div class="card mb-4">
        <div class="card-body">
            <h4>Status: <span class="text-<?= strpos($status, '✅') !== false ? 'success' : 'danger' ?>"><?= $status ?></span></h4>
            <div class="mt-3"><?= $details ?></div>
        </div>
    </div>

    <div class="alert alert-info">
        <strong>Secure config path used:</strong> 
        <?= htmlspecialchars(str_replace($_SERVER['DOCUMENT_ROOT'], '[WEB ROOT]', __DIR__ . '/../../../resupply_db_config.php')) ?>
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
        <a href="phpinfo.php" class="btn btn-outline-primary ms-3">View Full PHP Info</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>