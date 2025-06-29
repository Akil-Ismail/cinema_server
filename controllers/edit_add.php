<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once('../models/Movie.php');
require_once('../connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $result['error'] = 'Incorrect request method';
    $result['success'] = false;
    http_response_code(405);
    die(json_encode($result));
}
$result = [];


try {
    $movieData = [
        "name" => $_POST["name"] ?? "",
        "description" => $_POST["description"] ?? "",
        "genre" => $_POST["genre"] ?? "",
        "release_date" => $_POST["release_date"] ?? "",
        "running_time" => $_POST["running_time"] ?? "",
        "image" => $_POST["image"] ?? "",
        "language" => $_POST["language"] ?? "",
        "trailer" => $_POST["trailer"] ?? "",
        "hero_image" => $_POST["hero_image"] ?? ""
    ];

    // Remove empty fields from update/insert
    $movieData = array_filter($movieData, function ($v) {
        return $v !== "";
    });

    $movieModel = new Movie([]);

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        // Update
        if (empty($movieData)) {
            $result['error'] = 'No data to update';
            $result['success'] = false;
            http_response_code(400);
            echo json_encode($result);
            return;
        }
        $success = $movieModel->update(
            $mysqli,
            $movieData,
            $_POST['id']
        );
        if ($success) {
            $result['success'] = true;
            $result['message'] = 'Movie updated successfully';
        } else {
            $result['success'] = false;
            $result['error'] = 'Failed to update movie';
            http_response_code(500);
        }
    } else {
        // Insert
        if (empty($movieData)) {
            $result['error'] = 'No data to insert';
            $result['success'] = false;
            http_response_code(400);
            echo json_encode($result);
            return;
        }
        $success = $movieModel->insert($mysqli, $movieData);
        if ($success) {
            $result['success'] = true;
            $result['message'] = 'Movie added successfully';
        } else {
            $result['success'] = false;
            $result['error'] = 'Failed to add movie';
            http_response_code(500);
        }
    }
    echo json_encode($result);
    return;
} catch (Exception $e) {
    $result["success"] = false;
    $result['error'] = 'Something went wrong while updating movie';
    http_response_code(500);
    echo json_encode($result);
    return;
}
