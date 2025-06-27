<?php

require_once('../models/User.php');
require_once('../connection/connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $result['error'] = 'Incorrect request method';
    $result['success'] = false;
    http_response_code(405);
    die(json_encode($result));
}
$result = [];


try {
    $user = new User($_POST);

    if ($user->emailOrPhoneExists($mysqli)) {
        $result['error'] = 'Email or phone number already exists';
        $result['success'] = false;
        http_response_code(409);
        die(json_encode($result));
    }

    $user->insert($mysqli, $user->toArray());
    $result['data'] = $user;
    $result['success'] = true;
    echo json_encode($result);
    return;
} catch (Exception $e) {
    $result["success"] = false;
    $result['error'] = 'Something went wrong during registration';
    http_response_code(500);  // Internal Server Error
    echo json_encode($result);
    return;
}
