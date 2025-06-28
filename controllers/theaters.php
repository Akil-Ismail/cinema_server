<?php
require_once('../models/Theater.php');
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
    $theaterModel = new Theater([]);

    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $theaters = $theaterModel->select(
            $mysqli,
            [
                "id" => "",
                "standard_rows_count" => "",
                "columns_count" => "",
                "vip_rows_count" => "",
                "type_id" => "",
            ],
            [],
            [],
            ['id'],
            [$_GET['id']]
        );
        $result['data'] = $theaters;
        $result['success'] = true;
        echo json_encode($result);
        return;
    } else {
        $theaters = $theaterModel->select(
            $mysqli,
            [
                "id" => "",
                "standard_rows_count" => "",
                "columns_count" => "",
                "vip_rows_count" => "",
                "type_id" => "",
            ]
        );
        $result['data'] = $theaters;
        $result['success'] = true;
        echo json_encode($result);
        return;
    }
} catch (Exception $e) {
    $result["success"] = false;
    $result['error'] = 'Something went wrong while fetching theaters';
    http_response_code(500);  // Internal Server Error
    echo json_encode($result);
    return;
}
