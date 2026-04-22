<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
require_once 'db_config.php';
$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';
if ($username !== ADMIN_USERNAME || $password !== ADMIN_PASSWORD) {
    echo json_encode(['success' => false, 'message' => 'Invalid admin credentials']);
    exit;


cat > api/admin_login.php << 'EOF'
<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
require_once 'db_config.php';
$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';
if ($username !== ADMIN_USERNAME || $password !== ADMIN_PASSWORD) {
    echo json_encode(['success' => false, 'message' => 'Invalid admin credentials']);
    exit;
}
$token = bin2hex(random_bytes(32));
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("INSERT INTO admin_tokens (token, expires_at) VALUES (?, DATE_ADD(NOW(), INTERVAL 24 HOUR))");
    $stmt->execute([$token]);
    echo json_encode(['success' => true, 'message' => 'Admin login successful', 'token' => $token]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}
?>
