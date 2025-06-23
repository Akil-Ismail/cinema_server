<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once('../db/connection.php');

$result = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $result['error'] = 'Incorrect request method';
    $result['success'] = false;
    http_response_code(405);
    die(json_encode($result));
}

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'];

try {
    // Check for existing phone or email
    $checkSql = "SELECT * FROM `users` WHERE `phone` = ? OR `email` = ?";
    $checkStmt = $pdo->prepare($checkSql);
    $checkStmt->execute([$phone, $email]);
    $existingUser = $checkStmt->fetch();

    if ($existingUser) {
        $result["success"] = false;
        if ($existingUser['phone'] === $phone) {
            $result["error"] = "Phone number is already taken";
        } elseif ($existingUser['email'] === $email) {
            $result["error"] = "Email is already registered";
        } else {
            $result["error"] = "Phone number or email already exists";
        }
        http_response_code(409); // Conflict
        echo json_encode($result);
        exit();
    }

    // Insert new user
    $sql = "INSERT INTO `users` (`id`, `fname`, `lname` , `phone` , `email`, `password`) VALUES (NULL, ?, ?, ?, ?, ?)";
    $statement = $pdo->prepare($sql);
    $statement->bindValue(1, $fname);
    $statement->bindValue(2, $lname);
    $statement->bindValue(3, $phone);
    $statement->bindValue(4, $email);
    $statement->bindValue(5, password_hash($password, PASSWORD_DEFAULT));
    $statement->execute();

    $result["success"] = true;
    echo json_encode($result);
} catch (PDOException $e) {
    $result["success"] = false;
    $result['error'] = 'Something went wrong during registration';
    http_response_code(500);  // Internal Server Error
    echo json_encode($result);
}

$pdo = null;
