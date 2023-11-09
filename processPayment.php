<?php

require_once('bootstrap.php');

use Predis\Client;
use Src\Controller\PaymentController;

$redis = new Client();
$channelName = 'payment_channel';

$redis->pubSubLoop(function ($event) {
    if ($event->kind === 'message') {
        $paymentData = json_decode($event->payload, true);
        echo $paymentData;
        //(new PaymentController())->orchardPaymentControllerB($paymentData);
    }
}, $channelName);
