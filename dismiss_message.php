<?php
// dismiss_message.php - Modification Date: August 25, 2025, 12:00 PM - Total Lines: 25
require_once 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}
$user_id = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['msg_id'])) {
    $msg_id = intval($_POST['msg_id']);
    try {
        // Check if entry exists, insert if not (for idempotency)
        $stmt = $pdo->prepare("INSERT IGNORE INTO user_messages (user_id, message_id, dismissed) VALUES (:user, :msg, 0)");
        $stmt->execute(['user' => $user_id, 'msg' => $msg_id]);
        // Update to dismissed
        $stmt = $pdo->prepare("UPDATE user_messages SET dismissed = 1 WHERE user_id = :user AND message_id = :msg");
        $stmt->execute(['user' => $user_id, 'msg' => $msg_id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        error_log("Dismiss message error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Database error.']);
    }
} else {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
