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

    // Define columns to select, including the joined type attribute
    $columns = [
        "showtimes.id",
        "showtimes.time",
        "showtimes.date",
        "showtimes.movie_id",
        "showtimes.type_id",
        "types.type",
        "showtimes.theater_id"
    ];

    // JOIN clause to get type from types table
    $join = "INNER JOIN types ON showtimes.type_id = types.id";

    // If filtering by movie_id
    if (isset($_GET['movie_id']) && !empty($_GET['movie_id'])) {
        $showtimes = $showtimeModel->innerSelect(
            $mysqli,
            array_fill_keys($columns, ""), // keys as columns, values as empty string
            [],
            [],
            ['showtimes.movie_id'],
            [$_GET['movie_id']],
            $join
        );
        $result['data'] = $showtimes;
        $result['success'] = true;
        echo json_encode($result);
        return;
    } else {
        // Get all showtimes with type
        $showtimes = $showtimeModel->innerSelect(
            $mysqli,
            array_fill_keys($columns, ""),
            [],
            [],
            [],
            [],
            $join
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
