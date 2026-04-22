<?php
function getDBConnection() {
    $host = 'gateway01ap-southeast-1prod.alicloud.tidbcloud.com';
    $port = 4000;
    $dbname = 'godsteam_db';
    $dbuser = '3hJsMDBonyBCKF7.root';
    $dbpass = 'M0IBlMYXWYfPETOO';
    
    try {
        $pdo = new PDO(
            "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
            $dbuser,
            $dbpass
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        die(json_encode([
            'success' => false,
            'message' => 'Database connection failed: ' . $e->getMessage()
        ]));
    }
}
define('ADMIN_USERNAME', 'godsteam_admin');
define('ADMIN_PASSWORD', 'Admin@2026Secure');
?>
