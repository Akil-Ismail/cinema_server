<?php

require_once('../models/Movie.php');
require_once('../connection/connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    $result['error'] = 'Incorrect request method';
    $result['success'] = false;
    http_response_code(405);
    die(json_encode($result));
}
$result = [];

try {
    // Fetch all movies
    $movieModel = new Movie([
        "id" => "",
        "name" => "",
        "description" => "",
        "genre" => "",
        "release_date" => "",
        "running_time" => "",
        "image" => "",
        "language" => "",
        "hero_image" => ""
    ]);
    $movies = $movieModel->select($mysqli, [
        "id" => "",
        "name" => "",
        "description" => "",
        "genre" => "",
        "release_date" => "",
        "running_time" => "",
        "image" => "",
        "language" => "",
        "hero_image" => ""
    ]);

    $result['data'] = $movies;
    $result['success'] = true;
    echo json_encode($result);
    return;
} catch (Exception $e) {
    $result["success"] = false;
    $result['error'] = 'Something went wrong while fetching movies';
    http_response_code(500);  // Internal Server Error
    echo json_encode($result);
    return;
}
