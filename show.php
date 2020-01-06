<?php 

require 'PixReader.php';
$img = "img/test2.png";
$px = new PixReader;
$px->setImage($img);
$px->zoom=5;
$px->span=1;
$px->showImage();
$px->clearCache();