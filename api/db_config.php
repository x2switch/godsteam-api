<?php
function getDBConnection() {
    $host = 'gateway01ap-southeast-1prod.alicloud.tidbcloud.com';
    $port = 4000;
    $dbname = 'godsteam_db';
    $dbuser = '3hJsMDBonyBCKF7.root';
    $dbpass = 'M0IBlMYXWYfPETOO';
    $caPath = __DIR__ . '/ca.pem';
    $options = [
        PDO::MYSQL_ATTR_SSL_CA => $caPath,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass, $options);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        die(json_encode(['success' => false, 'message' => 'Database connection failed']));
    }
}
define('ADMIN_USERNAME', 'godsteam_admin');
define('ADMIN_PASSWORD', 'Admin@2026Secure');
?>
