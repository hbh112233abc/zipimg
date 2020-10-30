<?php
require __DIR__ . '/../vendor/autoload.php';

$input  = __DIR__ . '/input/3.png';
$output = __DIR__ . '/output/3.png';

$zip    = new \bingher\zipimg\PngQuant();
$result = $zip->input($input)->output($output)->zip();
var_dump($result);

$input  = __DIR__ . '/input/3.jpg';
$output = __DIR__ . '/output/3_g.jpg';

$zip    = new \bingher\zipimg\Guetzli();
$result = $zip->input($input)->output($output)->zip();
var_dump($result);

$input  = __DIR__ . '/input/3.jpg';
$output = __DIR__ . '/output/3_m.jpg';

$zip    = new \bingher\zipimg\Mozjpeg();
$result = $zip->input($input)->output($output)->zip();
var_dump($result);
