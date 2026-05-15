<?php
require_once 'includes/config.php';

$page_title = 'Shopping Lists';
require_once 'includes/header.php';
?>

<h1 class="mb-4">Your Shopping Lists</h1>

<a href="<?= BASE_URL ?>shopping_list_builder.php" class="btn btn-success mb-4">+ Create New Shopping List</a>

<div class="alert alert-info">
    Your shopping lists will appear here (scrollable tabs coming soon).
</div>

<?php require_once 'includes/footer.php'; ?>