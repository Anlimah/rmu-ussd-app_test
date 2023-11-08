<?php
phpinfo();
?>

<?php

require_once('bootstrap.php');

use Predis\Client;

$client = new Client();

// Enqueue a job
$client->lpush('ussd_pay', 'your_job_data_here');
?>