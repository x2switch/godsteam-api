<?php
function getDBConnection() {
    $host = 'ep-crimson-shadow-anu0o2l5-pooler.c-6.us-east-1.aws.neon.tech';
    $port = 5432;
    $dbname = 'neondb';
    $dbuser = 'neondb_owner';
    $dbpass = 'npg_thTz1SB3FPxI';
    
    try {
        $pdo = new PDO(
            "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require",
            $dbuser,
            $dbpass
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        http_response_code(500);
        die(json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]));
    }
}
define('ADMIN_USERNAME', 'godsteam_admin');
define('ADMIN_PASSWORD', 'Admin@2026Secure');
?>
