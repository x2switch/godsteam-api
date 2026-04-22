<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once 'db_config.php';
$admin_token = $_GET['admin_token'] ?? '';
if (empty($admin_token)) {
    echo json_encode(['success' => false, 'message' => 'Admin token required']);
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
    $stmt = $pdo->query("SELECT id, username, expire_date, max_devices, status, created_at, last_login FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($users as &$user) {
        $stmt = $pdo->prepare("SELECT COUNT(*) as device_count FROM devices WHERE user_id = ?");
        $stmt->execute([$user['id']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $user['device_count'] = $result['device_count'];
        if ($user['expire_date']) {
            $expire = strtotime($user['expire_date']);
            $now = time();
            $user['days_remaining'] = max(0, ceil(($expire - $now) / 86400));
        } else {
            $user['days_remaining'] = 'Lifetime';
        }
    }
    echo json_encode(['success' => true, 'users' => $users]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
