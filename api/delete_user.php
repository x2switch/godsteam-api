<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
require_once 'db_config.php';
$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'] ?? 0;
$admin_token = $input['admin_token'] ?? '';
if ($user_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id FROM admin_tokens WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$admin_token]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Invalid or expired admin token']);
        exit;
    }
    $stmt = $pdo->prepare("DELETE FROM devices WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
