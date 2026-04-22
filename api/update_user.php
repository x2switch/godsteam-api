<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
require_once 'db_config.php';
$input = json_decode(file_get_contents('php://input'), true);
$user_id = $input['user_id'] ?? 0;
$expire_date = $input['expire_date'] ?? null;
$max_devices = $input['max_devices'] ?? null;
$status = $input['status'] ?? null;
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
    $updates = [];
    $params = [];
    if ($expire_date !== null) {
        $updates[] = "expire_date = ?";
        $params[] = $expire_date ?: null;
    }
    if ($max_devices !== null) {
        $updates[] = "max_devices = ?";
        $params[] = $max_devices;
    }
    if ($status !== null) {
        $updates[] = "status = ?";
        $params[] = $status;
    }
    if (empty($updates)) {
        echo json_encode(['success' => false, 'message' => 'No fields to update']);
        exit;
    }
    $params[] = $user_id;
    $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
