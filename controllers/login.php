<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once('../connection/connection.php');
require_once('../models/User.php');

$result = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $result['error'] = 'incorrect request method';
    $result['success'] = false;
    http_response_code(405);
    die(json_encode($result));
}

$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;

if (!$username || !$password) {
    $input = json_decode(file_get_contents("php://input"), true);
    $username = $input['username'] ?? null;
    $password = $input['password'] ?? null;
}

if (empty($username) || empty($password)) {
    $result['error'] = 'Username and password are required';
    $result['success'] = false;
    http_response_code(400);
    echo json_encode($result);
    exit();
}

try {
    $userModel = new User([
        "id" => 0,
        "fname" => "",
        "lname" => "",
        "email" => "",
        "phone" => "",
        "password" => "",
        "payment" => 0
    ]);

    $users = $userModel->select(
        $mysqli,
        [
            "id" => "",
            "fname" => "",
            "lname" => "",
            "email" => "",
            "phone" => "",
            "password" => "",
            "payment" => ""
        ],
        [
            "email",
            "phone"
        ],
        [
            $username,
            $username
        ]
    );

    $userData = $users[0] ?? null;

    if ($userData && password_verify($password, $userData['password'])) {
        $result['success'] = true;
        $result['data'] = [
            "user_id" => $userData["id"],
            "email" => $userData["email"],
            "fname" => $userData["fname"],
            "lname" => $userData["lname"],
            "phone" => $userData["phone"],
            "payment" => $userData["payment"]
        ];
        echo json_encode($result);
        return;
    } elseif ($userData) {
        http_response_code(401);
        $result["error"] = "Incorrect password";
        $result["success"] = false;
        echo json_encode($result);
        return;
    } else {
        $result["error"] = "Incorrect username";
        $result["success"] = false;
        http_response_code(401);
        echo json_encode($result);
        return;
    }
} catch (Exception $e) {
    $result["success"] = false;
    $result['error'] = 'Something went wrong while logging in';
    http_response_code(500);
    echo json_encode($result);
    return;
}
