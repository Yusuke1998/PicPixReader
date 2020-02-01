<?php

require 'PixReader.php';
$img = "img/test6.png";

$px = new PixReader;
$px->setImage($img);

$px->zoom=5;
$px->span=1;
$px->lineSpace();
$px->Clustering();
$px->showImage();
$px->clearCache();