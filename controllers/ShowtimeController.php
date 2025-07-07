<?php
require_once('./models/Showtime.php');
require_once('./connection/connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, DELETE, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once("./services/ResponseService.php");





class ShowtimeController
{
    public function getShowtimes()
    {
        global $mysqli;
        try {
            $showtimeModel = new Showtime([]);
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
                    array_fill_keys($columns, ""),
                    [],
                    [],
                    ['showtimes.movie_id'],
                    [$_GET['movie_id']],
                    $join
                );
                echo ResponseService::success_response($showtimes);
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
                echo ResponseService::success_response($showtimes);
                return;
            }
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
        }
    }

    public function addShowtime()
    {
        global $mysqli;
        try {
            $showtimeData = [
                "time" => $_POST["time"] ?? "",
                "date" => $_POST["date"] ?? "",
                "theater_id" => $_POST["theater_id"] ?? "",
                "type_id" => $_POST["type_id"] ?? "",
                "movie_id" => $_POST["movie_id"] ?? ""
            ];

            $showtimeModel = new Showtime([]);
            $success = $showtimeModel->insert($mysqli, $showtimeData);
            echo ResponseService::success_response($success);
            return;
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
            return;
        }
    }

    public function deleteShowtime()
    {
        global $mysqli;
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = isset($input['id']) ? $input['id'] : null;
            $showtimeModel = new Showtime([]);
            $success = $showtimeModel->delete($mysqli, $id);
            echo ResponseService::success_response($success);
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
        }
    }
}
