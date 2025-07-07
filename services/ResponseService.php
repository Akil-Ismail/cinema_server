<?php

class ResponseService
{

    public static function success_response($data)
    {
        $response = [];
        $response["success"] = true;
        $response["data"] = $data;
        return json_encode($response);
    }
    public static function fail_response($e)
    {
        $response = [];
        $response["status"] = 404;
        $response["payload"] = "$e";
        return json_encode($response);
    }
}
