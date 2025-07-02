<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once('../models/Ticket.php');
require_once('../connection/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$result = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $user_id = $_POST['user_id'] ?? null;
        $theater_id = $_POST['theater_id'] ?? null;
        $showtime_id = $_POST['showtime_id'] ?? null;
        $seat = $_POST['seat'] ?? null;

        if (!$user_id || !$theater_id || !$showtime_id || !$seat) {
            $result['success'] = false;
            $result['error'] = 'All fields are required';
            http_response_code(400);
            echo json_encode($result);
            return;
        }

        $ticketData = [
            'user_id' => $user_id,
            'theater_id' => $theater_id,
            'showtime_id' => $showtime_id,
            'seat' => $seat
        ];

        $ticketModel = new Ticket($ticketData);
        $success = $ticketModel->insert($mysqli, $ticketData);

        if ($success) {
            $result['success'] = true;
            $result['message'] = 'Ticket created successfully';
        } else {
            $result['success'] = false;
            $result['error'] = 'Failed to create ticket';
            http_response_code(500);
        }
        echo json_encode($result);
        return;
    } catch (Exception $e) {
        $result['success'] = false;
        $result['error'] = 'Something went wrong while creating ticket';
        http_response_code(500);
        echo json_encode($result);
        return;
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $theater_id = $_GET['theater_id'] ?? null;
        $showtime_id = $_GET['showtime_id'] ?? null;

        $ticketModel = new Ticket([]);

        $columns = [
            'user_id' => '',
            'theater_id' => '',
            'showtime_id' => '',
            'seat' => ''
        ];

        $whereKeys = [];
        $whereParams = [];

        if ($theater_id !== null) {
            $whereKeys[] = 'theater_id';
            $whereParams[] = $theater_id;
        }
        if ($showtime_id !== null) {
            $whereKeys[] = 'showtime_id';
            $whereParams[] = $showtime_id;
        }

        $tickets = $ticketModel->select(
            $mysqli,
            $columns,
            [],
            [],
            $whereKeys,
            $whereParams
        );

        $result['success'] = true;
        $result['data'] = $tickets;
        echo json_encode($result);
        return;
    } catch (Exception $e) {
        $result['success'] = false;
        $result['error'] = 'Something went wrong while fetching tickets';
        http_response_code(500);
        echo json_encode($result);
        return;
    }
}
