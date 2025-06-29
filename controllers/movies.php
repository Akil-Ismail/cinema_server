<?php

require_once('../models/Movie.php');
require_once('../connection/connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = [];

    try {
        // Fetch all movies
        $movieModel = new Movie([]);

        if (isset($_GET['id']) || !empty($_GET['id'])) {
            $movies = $movieModel->select(
                $mysqli,
                [
                    "id" => "",
                    "name" => "",
                    "description" => "",
                    "genre" => "",
                    "release_date" => "",
                    "running_time" => "",
                    "image" => "",
                    "trailer" => "",
                    "language" => "",
                    "hero_image" => ""
                ],
                [],
                [],
                ['id'],
                [$_GET['id']]
            );
            $result['data'] = $movies;
            $result['success'] = true;
            echo json_encode($result);
            return;
        }

        $movies = $movieModel->select($mysqli, [

            "id" => "",
            "name" => "",
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
}
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $result = [];
    try {
        // Get raw input and decode JSON
        $input = json_decode(file_get_contents('php://input'), true);
        $id = isset($input['id']) ? $input['id'] : null;

        if (!empty($id)) {
            $movieModel = new Movie([]);
            $success = $movieModel->delete($mysqli, $id);
            if ($success) {
                $result['success'] = true;
                $result['message'] = 'Movie deleted successfully';
            } else {
                $result['success'] = false;
                $result['error'] = 'Failed to delete movie';
                http_response_code(500);
            }
        } else {
            $result['success'] = false;
            $result['error'] = 'Invalid movie ID';
            http_response_code(400);
        }
    } catch (Exception $e) {
        $result["success"] = false;
        $result['error'] = 'Something went wrong while deleting movie';
        http_response_code(500);
    }
    echo json_encode($result);
    return;
}
