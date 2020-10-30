<?php
require_once '../vendor/autoload.php';

use bingher\transmit\Client;

$client = new Client('127.0.0.1', 8000);
$params = [
    'in_img'  => __DIR__ . '/input/1.jpg',
    'out_img' => __DIR__ . '/output/1.test.jpg',
];
$result = $client->zip($params);
var_dump($result);
