<?php

require_once('./models/Movie.php');
require_once('./connection/connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once("./services/ResponseService.php");



class MovieController
{
    public function getMovies()
    {
        global $mysqli;
        try {
            $movieModel = new Movie([]);
            $info = [

                "id" => "",
                "name" => "",
                "hero_image" => "",
                "image" => ""
            ];
            $movies = $movieModel->select($mysqli, $info);
            echo ResponseService::success_response($movies);
            return;
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
        }
    }

    public function getMovie()
    {
        global $mysqli;
        try {
            $movieModel = new Movie([]);
            $info =
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
                ];
            $movies = $movieModel->select(
                $mysqli,
                $info,
                [],
                [],
                ['id'],
                [$_GET['id']]
            );
            echo ResponseService::success_response($movies);
            return;
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
        }
    }

    public function deleteMovie()
    {
        global $mysqli;
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = isset($input['id']) ? $input['id'] : null;

            if (!empty($id)) {
                $movieModel = new Movie([]);
                $success = $movieModel->delete($mysqli, $id);
                ResponseService::success_response($success);
            }
        } catch (Exception $e) {
            ResponseService::fail_response($e);
        }
    }
    public function updateMovie()
    {
        global $mysqli;
        try {
            $movieData =
                [
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

            $movieData = array_filter($movieData, function ($v) {
                return $v !== "";
            });

            $movieModel = new Movie([]);
            $success = $movieModel->update(
                $mysqli,
                $movieData,
                $_POST['id']
            );
            ResponseService::success_response($success);
        } catch (Exception $e) {
            ResponseService::fail_response($e);
        }
    }
    public function addMovie()
    {
        global $mysqli;
        $movieData =
            [
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
        $movieModel = new Movie([]);

        try {
            $success = $movieModel->insert($mysqli, $movieData);
            ResponseService::success_response($success);
        } catch (Exception $e) {
            ResponseService::fail_response($e);
        }
    }
}
