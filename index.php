<?php

require 'PixReader.php';
// dd(gd_info());

// $img = "img/small.png";
// $img = "img/jj.png";
// $img = "img/escalera.png";
// $img = "img/escaleras.png";
// $img = "img/figuras.png";
// $img = "img/star.png";
$img = "img/lol.png";
$px = new PixReader;
$px->setImage($img);

$px->zoom=3;
$px->span=1;

// Si la imagen tiene colores extra, hay que pasarla a gris y pintarla si es necesario (blanco y negro)
// $px->image2gray();
// $px->paintPixel(0,white,0);
// $px->paintPixel(white,0,0);
// $px->squelettisation();

$px->Clustering();

$px->showImage();
// $px->saveImage();
$px->clearCache();