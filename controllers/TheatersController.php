<?php
require_once('./models/Theater.php');
require_once('./connection/connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once("./services/ResponseService.php");


class TheatersController
{
    public function getTheater()
    {
        global $mysqli;
        try {
            $theaterModel = new Theater([]);
            $theater = [
                "id" => "",
                "standard_rows_count" => "",
                "columns_count" => "",
                "vip_rows_count" => "",
                "type_id" => "",
            ];
            $theaters = $theaterModel->select(
                $mysqli,
                $theater,
                [],
                [],
                ['id'],
                [$_GET['id']]
            );
            echo ResponseService::success_response($theaters);
            return;
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
            return;
        }
    }

    public function getTheaters()
    {
        global $mysqli;
        try {
            $theaterModel = new Theater([]);
            $theater = [
                "id" => "",
                "standard_rows_count" => "",
                "columns_count" => "",
                "vip_rows_count" => "",
                "type_id" => "",
            ];
            $theaters = $theaterModel->select(
                $mysqli,
                $theater
            );
            echo ResponseService::success_response($theaters);
            return;
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
            return;
        }
    }
}
