<?php

require_once('bootstrap.php');

use Predis\Client;
use Src\Controller\PaymentController;

$redis = new Client();

// Define the Redis channel name
$channelName = 'payment_channel';

// Subscribe to the Redis channel
$redis->pubSubLoop(function ($event) {
    if ($event->kind === 'message') {
        $paymentData = json_decode($event->payload, true);
        (new PaymentController())->orchardPaymentControllerB($paymentData);
    }
}, $channelName);

// The script will keep running and listening for messages until manually stopped.
