<?php 

require 'PixReader.php';

$px = new PixReader;

$img = "img/cuadrados.png";

$px->setImage($img);

$px->zoom=2;
$px->span=1;

$px->showImage();
$px->clearCache();