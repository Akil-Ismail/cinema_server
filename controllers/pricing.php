<?php
require_once('../models/Pricing.php');
require_once('../connection/connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $result = [];

    try {
        $pricingModel = new Pricing([]);

        if (isset($_GET['type_id']) && !empty($_GET['type_id'])) {
            $pricings = $pricingModel->select(
                $mysqli,
                [
                    "id" => "",
                    "regular" => "",
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
        }
        $join = "INNER JOIN types ON pricing.type_id = types.id";
        $pricings = $pricingModel->innerSelect(
            $mysqli,
            [
                "pricing.id" => "",
                "pricing.regular" => "",
                "pricing.vip" => "",
                "pricing.type_id" => "",
                "types.type" => ""
            ],
            [],
            [],
            [],
            [],
            $join
        );
        $result['data'] = $pricings;
        $result['success'] = true;
        echo json_encode($result);
        return;
    } catch (Exception $e) {
        $result["success"] = false;
        $result['error'] = 'Something went wrong while fetching pricing';
        http_response_code(500);  // Internal Server Error
        echo json_encode($result);
        return;
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $result = [];
    try {
        $id = $_POST['id'] ?? null;
        $regular = $_POST['regular'] ?? null;
        $vip = $_POST['vip'] ?? null;

        if (empty($id)) {
            $result['success'] = false;
            $result['error'] = 'ID is required for update';
            http_response_code(400);
            echo json_encode($result);
            return;
        }

        $updateData = [];
        if ($regular !== null) $updateData['regular'] = $regular;
        if ($vip !== null) $updateData['vip'] = $vip;

        if (empty($updateData)) {
            $result['success'] = false;
            $result['error'] = 'No data to update';
            http_response_code(400);
            echo json_encode($result);
            return;
        }

        $pricingModel = new Pricing([]);
        $success = $pricingModel->update(
            $mysqli,
            $updateData,
            $id
        );

        if ($success) {
            $result['success'] = true;
            $result['message'] = 'Pricing updated successfully';
        } else {
            $result['success'] = false;
            $result['error'] = 'Failed to update pricing';
            http_response_code(500);
        }
        echo json_encode($result);
        return;
    } catch (Exception $e) {
        $result["success"] = false;
        $result['error'] = 'Something went wrong while updating pricing';
        http_response_code(500);
        echo json_encode($result);
        return;
    }
}
