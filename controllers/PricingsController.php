<?php
require_once('./models/Pricing.php');
require_once('./connection/connection.php');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once("./services/ResponseService.php");


class PricingsController
{
    public function getPricing()
    {
        global $mysqli;
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
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
            return;
        }
    }

    public function getPricings()
    {
        global $mysqli;
        try {
            $pricingModel = new Pricing([]);
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
            echo ResponseService::success_response($pricings);
            return;
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
            return;
        }
    }

    public function setPricings()
    {
        global $mysqli;
        try {
            $id = $_POST['id'] ?? null;
            $regular = $_POST['regular'] ?? null;
            $vip = $_POST['vip'] ?? null;

            $updateData = [];
            if ($regular !== null) $updateData['regular'] = $regular;
            if ($vip !== null) $updateData['vip'] = $vip;

            $pricingModel = new Pricing([]);
            $success = $pricingModel->update(
                $mysqli,
                $updateData,
                $id
            );

            echo ResponseService::success_response($success);
            return;
        } catch (Exception $e) {
            echo ResponseService::success_response($e);
            return;
        }
    }
}
