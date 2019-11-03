<?php

require 'PixReader.php';
// dd(gd_info());

$img = "img/pro.png";
$px = new PixReader;
$px->setImage($img);

$px->zoom=2;
$px->span=1;

// $px->image2gray();
// $px->paintPixel(white,0,0);
// $px->squelettisation();

// $px->Clustering();
$px->Test();

$px->showImage();
// $px->saveImage();
$px->clearCache();