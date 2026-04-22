<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
require_once 'db_config.php';
$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';
$expire_date = $input['expire_date'] ?? '';
$max_devices = $input['max_devices'] ?? 1;
$admin_token = $input['admin_token'] ?? '';
if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Username and password required']);
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
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Username already exists']);
        exit;
    }
    $stmt = $pdo->prepare("INSERT INTO users (username, password, expire_date, max_devices, status) VALUES (?, MD5(?), ?, ?, 'active')");
    $stmt->execute([$username, $password, $expire_date ?: null, $max_devices]);
    echo json_encode(['success' => true, 'message' => 'User created successfully', 'user_id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
