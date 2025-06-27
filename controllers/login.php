<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once('../connection/connection.php');

$result = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $result['error'] = 'incorrect request method';
    $result['success'] = false;
    http_response_code(405);
    die(json_encode($result));
}

// $post = file_get_contents("php://input");

$username = $_POST['username'];
$password = $_POST['password'];

// echo json_encode($_POST);
// die();

$sql = "SELECT `id` as `user_id`, `fname`,`lname`, `email`, `password` FROM `users` WHERE email = ? or phone = ?;";
$statement = $pdo->prepare($sql);
$statement->bindValue(1, $username);
$statement->bindValue(2, $username);
$statement->execute();
$userData = $statement->fetch();


if ($userData) {
    if (password_verify($password, $userData['password'])) {
        $result['success'] = true;
        $user = [
            "user_id" => $userData["user_id"],
            "email" => $userData["email"],
            "fname" => $userData["fname"],
            "lname" => $userData["lname"]
        ];
        $result['data'] = $user;
        echo json_encode($result);
    } else {
        http_response_code(401);
        $result["error"] = "incorrect password";
        $result["success"] = false;
        echo json_encode($result);
    }
} else {
    $result["error"] = "incorrect username";
    $result["success"] = false;
    echo json_encode($result);
}


$pdo = null;
