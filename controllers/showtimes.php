<?php
require_once('../models/Showtime.php');
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
    $showtimeModel = new Showtime([]);

    // If filtering by movie_id
    if (isset($_GET['movie_id']) && !empty($_GET['movie_id'])) {
        $showtimes = $showtimeModel->select(
            $mysqli,
            [
                "id" => "",
                "time" => "",
                "date" => "",
                "movie_id" => ""
            ],
            [],
            [],
            ['movie_id'],
            [$_GET['movie_id']]
        );
        $result['data'] = $showtimes;
        $result['success'] = true;
        echo json_encode($result);
        return;
    } else {
        // Get all showtimes
        $showtimes = $showtimeModel->select(
            $mysqli,
            [
                "id" => "",
                "time" => "",
                "date" => "",
                "movie_id" => ""
            ]
        );
        $result['data'] = $showtimes;
        $result['success'] = true;
        echo json_encode($result);
        return;
    }
} catch (Exception $e) {
    $result["success"] = false;
    $result['error'] = 'Something went wrong while fetching showtimes';
    http_response_code(500);  // Internal Server Error
    echo json_encode($result);
    return;
}
