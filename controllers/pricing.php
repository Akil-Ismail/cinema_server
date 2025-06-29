<?php
require_once('../models/Pricing.php');
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
    $pricingModel = new Pricing([]);

    if (isset($_GET['type_id']) && !empty($_GET['type_id'])) {
        $pricings = $pricingModel->select(
            $mysqli,
            [
                "id" => "",
                "standard" => "",
                "vip" => "",
                "type_id" => ""
            ],
            [],
            [],
            ['type_id'],
            [$_GET['type_id']]
        );
        $result['data'] = $pricings;
        $result['success'] = true;
        echo json_encode($result);
        return;
    } else {
        $result['error'] = 'type_id parameter is required';
        $result['success'] = false;
        http_response_code(400);
        echo json_encode($result);
        return;
    }
} catch (Exception $e) {
    $result["success"] = false;
    $result['error'] = 'Something went wrong while fetching pricing';
    http_response_code(500);  // Internal Server Error
    echo json_encode($result);
    return;
}
