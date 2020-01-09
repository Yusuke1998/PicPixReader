<?php 

require 'PixReader.php';
$img = "img/test2.png";
$px = new PixReader;
$px->setImage($img);
$px->zoom=5;
$px->span=2;
// $px->lineSpace();
#$px->Clustering();
$px->showImage();
$px->clearCache();