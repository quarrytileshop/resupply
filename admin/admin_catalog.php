<?php
/**
 * resupply - Admin Catalog Page (Professional Rewrite)
 * Global catalog management for super-admins
 * Date: May 15, 2026
 */

require_once '../includes/config.php';

if (!is_super_admin()) {
    header("Location: " . BASE_URL . "dashboard.php");
    exit;
}

$page_title = 'Catalog Management';

require_once '../includes/header.php';
?>

<h1 class="mb-4">Global Catalog</h1>
<p class="lead">Manage products available to all vendors and organizations.</p>

<div class="alert alert-info">
    Catalog editing interface will be expanded in Batch 4 (full CRUD + bulk import).
    Current version shows placeholder for now.
</div>

<a href="<?= BASE_URL ?>admin/bulk_import.php" class="btn btn-primary">Bulk Import Products</a>

<?php require_once '../includes/footer.php'; ?>