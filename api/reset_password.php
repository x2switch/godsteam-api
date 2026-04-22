<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
require_once 'db_config.php';
$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'] ?? 0;
$new_password = $input['new_password'] ?? '';
$admin_token = $input['admin_token'] ?? '';
if ($user_id <= 0 || empty($new_password)) {
    echo json_encode(['success' => false, 'message' => 'User ID and new password required']);
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
    $stmt = $pdo->prepare("UPDATE users SET password = MD5(?) WHERE id = ?");
    $stmt->execute([$new_password, $user_id]);
    echo json_encode(['success' => true, 'message' => 'Password reset successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
