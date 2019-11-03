<?php 

require 'PixReader.php';

$px = new PixReader;

$img = "img/escalera.png";

$px->setImage($img);

$px->zoom=2;
$px->span=1;

// $px->image2gray();
// $px->paintPixel(white,0,0);
// $px->squelettisation();

$px->Test();

$px->showImage();
// $px->saveImage();
$px->clearCache();