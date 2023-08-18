<?php

require_once('bootstrap.php');

$redis = new Predis\Client();
$redis->set('name', 'Francis Anlimah');
echo $redis->get('name');
