<?php
header('Content-Type: application/json');
require_once 'db_config.php';
echo json_encode(['status' => 'ok']);
?>
