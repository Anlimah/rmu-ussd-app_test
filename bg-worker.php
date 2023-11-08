<?php
require_once('bootstrap.php');

use Src\Controller\PaymentController;
use Predis\Client;

$client = new Client();

echo "Waiting...\n";

while (true) {
    try {
        $job = $client->brpop('ussd_pay', 0);
        if ($job !== false) {
            sleep(3);
            file_put_contents("content.txt", $job[1] . PHP_EOL);
            echo $job[1] . PHP_EOL;
            //(new PaymentController())->orchardPaymentControllerB($payData);
        }
    } catch (\Throwable $th) {
        file_put_contents("content.txt", $th->getMessage());
    }
}
