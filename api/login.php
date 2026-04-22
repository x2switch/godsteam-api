<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$input = json_decode(file_get_contents('php://input'), true);

$username = $input['username'] ?? '';
$password = $input['password'] ?? '';
$device_id = $input['device_id'] ?? '';

$valid_username = 'admin';
$valid_password = '123456';

$response = [];

if ($username === $valid_username && $password === $valid_password) {
    $response['success'] = true;
    $response['message'] = 'Login successful';
    $response['username'] = $username;
    $response['expire_date'] = '2026-12-31';
} else {
    $response['success'] = false;
    $response['message'] = 'Invalid username or password';
}

echo json_encode($response);
?>
