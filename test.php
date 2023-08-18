<?php

require_once('bootstrap.php');

$redis = new Predis\Client();

echo $redis->ping();
