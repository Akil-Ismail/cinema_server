<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTION");
header("Access-Control-Allow-Headers: Content-Type");
require_once('./connection/connection.php');
require_once('./models/User.php');
require_once("./services/ResponseService.php");

class UserController
{
    public function login()
    {
        global $mysqli;
        try {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User([]);

            $data =
                [
                    "id" => "",
                    "fname" => "",
                    "lname" => "",
                    "email" => "",
                    "phone" => "",
                    "password" => "",
                ];
            $orKeys =
                [
                    "email",
                    "phone"
                ];
            $orParams =
                [
                    $username,
                    $username
                ];

            $users = $userModel->select($mysqli, $data, $orKeys, $orParams);

            $userData = $users[0] ?? null;

            if ($userData && password_verify($password, $userData['password'])) {
                echo ResponseService::success_response($userData);
            } else {
                echo ResponseService::fail_response("You have entered a wrong password");
            }
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
        }
    }

    public function signup()
    {
        global $mysqli;
        try {
            $_POST['payment'] = 0;
            $user = new User($_POST);

            if ($user->emailOrPhoneExists($mysqli)) {
                $result['error'] = 'Email or phone number already exists';
                $result['success'] = false;
                http_response_code(409);
                die(json_encode($result));
            }

            $user->insert($mysqli, $user->toArray());
            echo ResponseService::success_response($user);
            return;
        } catch (Exception $e) {
            echo ResponseService::fail_response($e);
        }
    }
}
