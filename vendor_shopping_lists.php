<?php
// vendor_shopping_lists.php – Updated with vendor_id isolation – 2026-05-11
$page_title = "My Shopping Lists - Resupply Rocket";
require_once 'header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_organization_admin']) || !$_SESSION['is_organization_admin']) {
    header("Location: dashboard.php");
    exit;
}

$vendor_id = $_SESSION['vendor_id'] ?? 0;

$stmt = $pdo->prepare("SELECT sl.*, o.name as org_name 
                       FROM shopping_lists sl 
                       LEFT JOIN organizations o ON sl.organization_id = o.id 
                       WHERE sl.vendor_id = :vendor_id 
                       ORDER BY sl.created_at DESC");
$stmt->execute(['vendor_id' => $vendor_id]);
$lists = $stmt->fetchAll();
?>

<div class="container mt-4">
    <h1 class="mb-3">My Shopping Lists</h1>
    <!-- table of lists filtered by vendor_id -->
    <!-- ... same structure as before ... -->
</div>

<?php require_once 'footer.php'; ?>
