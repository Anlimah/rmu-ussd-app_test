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

        if (!empty($payData)) (new Client())->publish('paymentChannel', json_encode($payData));
        else file_put_contents(
            'processUSSD.log',
            date('Y-m-d H:i:s') . "No payment data available.\n" . json_encode($response) . "\n",
            FILE_APPEND
        );
        break;

    default:
        header("HTTP/1.1 403 Forbidden");
        header("Content-Type: text/html");
        break;
}

exit();
