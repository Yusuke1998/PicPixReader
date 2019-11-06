<?php

require 'PixReader.php';
// dd(gd_info());

$img = "img/small.png";
// $img = "img/pro.png";
$px = new PixReader;
$px->setImage($img);

$px->zoom=4;
$px->span=1;

// $px->image2gray();
// $px->paintPixel(white,0,0);
// $px->squelettisation();

$px->Test();
// $px->Clustering();

$px->showImage();
// $px->saveImage();
$px->clearCache();