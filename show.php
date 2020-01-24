<?php 

require 'PixReader.php';
$img = "img/test3.png";
$px = new PixReader;
$px->setImage($img);
$px->zoom=4;
$px->span=3;
// $px->lineSpace();
// $px->Clustering();
$px->showImage();
$px->clearCache();