<?php
// dismiss_message.php – Modified 2026-05-08 – Lines: 45
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['msg_id'])) {
    http_response_code(400);
    exit;
}

$user_id = $_SESSION['user_id'];
$msg_id  = intval($_POST['msg_id']);

try {
    $stmt = $pdo->prepare("INSERT INTO user_messages (user_id, message_id, dismissed) 
                           VALUES (:user_id, :msg_id, 1) 
                           ON DUPLICATE KEY UPDATE dismissed = 1");
    $stmt->execute([
        'user_id' => $user_id,
        'msg_id'  => $msg_id
    ]);
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false]);
}
?>
