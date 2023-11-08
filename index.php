<?php

require_once('bootstrap.php');

use Src\Controller\USSDHandler;
use Predis\Client;

switch ($_SERVER["REQUEST_METHOD"]) {
    case 'POST':
        $_POST = json_decode(file_get_contents("php://input"), true);
        $response = array();
        $payData = array();

        if (!empty($_POST)) $response = (new USSDHandler($_POST))->run();

        if (isset($response["data"])) {
            $payData = $response["data"];
            unset($response["data"]);
        }

        header("Content-Type: application/json");
        echo json_encode($response);

        if (!empty($payData)) (new Client())->lpush('ussd_pay', $payData);
        break;

    default:
        header("HTTP/1.1 403 Forbidden");
        header("Content-Type: text/html");
        break;
}
