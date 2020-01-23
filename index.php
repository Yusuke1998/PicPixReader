<?php

require 'PixReader.php';
$img = "img/test3.png";
$px = new PixReader;
$px->setImage($img);
$px->zoom=5;
$px->span=2;
$px->lineSpac3();
// $px->Clustering();
$px->showImage();
$px->clearCache();