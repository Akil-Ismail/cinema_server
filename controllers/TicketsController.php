<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
require_once('./models/Ticket.php');
require_once('./connection/connection.php');
require_once("./services/ResponseService.php");


class ticketController
{
    public function getTickets()
    {
        global $mysqli;
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
            ResponseService::success_response($tickets);
            return;
        } catch (Exception $e) {
            ResponseService::fail_response($e);
            return;
        }
    }

    public function addTickets()
    {
        global $mysqli;
        try {
            $ticketsArray = $_POST['tickets'] ?? null;

            if (!$ticketsArray) {
                $input = json_decode(file_get_contents("php://input"), true);
                $ticketsArray = $input['tickets'] ?? null;
            }

            foreach ($ticketsArray as $ticket) {
                $user_id = $ticket['user_id'] ?? null;
                $theater_id = $ticket['theater_id'] ?? null;
                $showtime_id = $ticket['showtime_id'] ?? null;
                $seat = $ticket['seat'] ?? null;
                $row = $ticket['row'] ?? null;

                $ticketData = [
                    'user_id' => $user_id,
                    'theater_id' => $theater_id,
                    'showtime_id' => $showtime_id,
                    'seat' => $seat,
                    'row' => $row
                ];

                $ticketModel = new Ticket($ticketData);
                $success = $ticketModel->insert($mysqli, $ticketData);
                ResponseService::success_response($success);
                return;
            }
        } catch (Exception $e) {
            ResponseService::fail_response($e);
        }
    }
}
