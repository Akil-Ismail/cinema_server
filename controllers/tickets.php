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
        // Check if tickets array is sent (for bulk insert)
        $ticketsArray = $_POST['tickets'] ?? null;

        // If not, try to get JSON body (for application/json requests)
        if (!$ticketsArray) {
            $input = json_decode(file_get_contents("php://input"), true);
            $ticketsArray = $input['tickets'] ?? null;
        }

        // If tickets array is present, handle bulk insert
        if ($ticketsArray && is_array($ticketsArray)) {
            $hasError = false;
            $errorMessage = '';
            foreach ($ticketsArray as $ticket) {
                $user_id = $ticket['user_id'] ?? null;
                $theater_id = $ticket['theater_id'] ?? null;
                $showtime_id = $ticket['showtime_id'] ?? null;
                $seat = $ticket['seat'] ?? null;
                $row = $ticket['row'] ?? null;

                if (!$user_id || !$theater_id || !$showtime_id || !$seat) {
                    $hasError = true;
                    $errorMessage = 'All fields are required';
                    break;
                }

                $ticketData = [
                    'user_id' => $user_id,
                    'theater_id' => $theater_id,
                    'showtime_id' => $showtime_id,
                    'seat' => $seat,
                    'row' => $row
                ];

                $ticketModel = new Ticket($ticketData);
                $success = $ticketModel->insert($mysqli, $ticketData);

                if (!$success) {
                    $hasError = true;
                    $errorMessage = 'Failed to create ticket';
                    break;
                }
            }

            if ($hasError) {
                $result['success'] = false;
                $result['error'] = $errorMessage;
                http_response_code(400);
            } else {
                $result['success'] = true;
                $result['message'] = 'All tickets created successfully';
            }
            echo json_encode($result);
            return;
        }

        // Fallback: single ticket insert (legacy)
        $user_id = $_POST['user_id'] ?? null;
        $theater_id = $_POST['theater_id'] ?? null;
        $showtime_id = $_POST['showtime_id'] ?? null;
        $seat = $_POST['seat'] ?? null;
        $row = $_POST['row'] ?? null;

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
            'seat' => $seat,
            'row' => $row
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
            'seat' => '',
            'row' => ''
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
