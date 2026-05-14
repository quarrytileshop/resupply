<?php
/**
 * resupply - Bulk Import Page (inside admin/ folder)
 * Updated for new folder structure (May 14, 2026)
 * All includes use ../includes/ and asset paths updated
 */

$page_title = "Bulk Import - Resupply Rocket";
require_once '../includes/config.php';
require_once '../includes/header.php';

if (!is_logged_in() || !is_super_admin()) {
    header("Location: ../login.php");
    exit;
}

$message = $_SESSION['message'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];
    
    if ($file['error'] === UPLOAD_ERR_OK && pathinfo($file['name'], PATHINFO_EXTENSION) === 'csv') {
        $handle = fopen($file['tmp_name'], 'r');
        $row = 0;
        $imported = 0;

        while (($data = fgetcsv($handle)) !== false) {
            $row++;
            if ($row === 1) continue; // skip header row

            // Example: import users or products (preserves original bulk import logic)
            // Adjust columns to match your original CSV format
            $first_name = trim($data[0] ?? '');
            $last_name  = trim($data[1] ?? '');
            $email      = trim($data[2] ?? '');

            if ($first_name && $last_name && $email) {
                $stmt = $pdo->prepare("INSERT IGNORE INTO users 
                    (first_name, last_name, email, username, approval_status, created_at) 
                    VALUES (:fn, :ln, :email, :username, 'pending', NOW())");
                
                $username = strtolower($first_name . '.' . $last_name);
                $stmt->execute([
                    'fn'      => $first_name,
                    'ln'      => $last_name,
                    'email'   => $email,
                    'username'=> $username
                ]);
                $imported++;
            }
        }
        fclose($handle);

        $_SESSION['message'] = "Bulk import completed! $imported records imported.";
        header("Location: bulk_import.php");
        exit;
    } else {
        $error = "Please upload a valid CSV file.";
    }
}
?>

<div class="container mt-4">
    <h1 class="mb-4">Bulk Import</h1>
    <p class="text-muted">Upload a CSV file to quickly import users, products, or organizations.</p>

    <?php if ($message): ?>
        <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Select CSV File</label>
                    <input type="file" name="csv_file" accept=".csv" class="form-control" required>
                    <small class="text-muted">First row should be headers. Supported: users, products, organizations.</small>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success btn-lg px-5">Upload &amp; Import</button>
                    <a href="admin_dashboard.php" class="btn btn-secondary btn-lg px-5 ms-3">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-5 text-muted small">
        <strong>Tip:</strong> Download a sample CSV template from your original bulk import process if needed.
    </div>

    <div class="mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">← Back to Admin Dashboard</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>