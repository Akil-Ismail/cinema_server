<?php
require_once('../models/Showtime.php');
require_once('../connection/connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, DELETE, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = [];

    try {
        $showtimeModel = new Showtime([]);

        // Define columns to select, including the joined type attribute
        $columns = [
            "showtimes.id",
            "showtimes.time",
            "showtimes.date",
            "showtimes.movie_id",
            "showtimes.theater_id",
            "movies.name",
            "showtimes.type_id",
            "types.type"
        ];

        // JOIN clause to get type from types table
        $join = "INNER JOIN types ON showtimes.type_id = types.id INNER JOIN movies ON showtimes.movie_id = movies.id";

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
            $showtimes = $showtimeModel->select(
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
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $result = [];
    try {
        // Get raw input and decode JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $id = isset($input['id']) ? $input['id'] : null;

        if (!empty($id)) {
            $showtimeModel = new Showtime([]);
            $success = $showtimeModel->delete($mysqli, $id);
            if ($success) {
                $result['success'] = true;
                $result['message'] = 'Showtime deleted successfully';
            } else {
                $result['success'] = false;
                $result['error'] = 'Failed to delete showtime';
                http_response_code(500);
            }
        } else {
            $result['success'] = false;
            $result['error'] = 'Invalid showtime ID';
            http_response_code(400);
        }
    } catch (Exception $e) {
        $result["success"] = false;
        $result['error'] = 'Something went wrong while deleting showtime';
        http_response_code(500);
    }
    echo json_encode($result);
    return;
}
