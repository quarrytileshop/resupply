<?php
// approve_vendor.php – Approve pending vendor applications – 2026-05-11
require_once 'config.php';
session_start();

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin'] || !isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("UPDATE users SET approval_status = 'approved' WHERE id = :id AND is_organization_admin = 1");
$stmt->execute(['id' => $id]);

header("Location: admin_dashboard.php?msg=approved");
exit;
