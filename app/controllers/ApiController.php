<?php

namespace app\controllers;

class ApiController
{
    private function requestStatus($code)
    {
        $codes  = include(__DIR__ . '/../config/http_codes.php');

        return (isset($codes[$code])) ? $codes[$code] : $codes[500];
    }

    public function responseWithView($data, $status = 200)
    {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        echo $data;
    }

    public function responseWithJson($data, $status = 200)
    {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        echo json_encode($data);
    }

    public function responseWithErrors($errors, $code = 500)
    {
        $errorData  = array('errors' => $errors);
        return $this->responseWithJson($errorData, $code);
    }
}
