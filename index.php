<?php 

require 'PixReader.php';

$px = new PixReader;
$img = "img/diagonal-white.png";
// $img = "img/diagonal-blue.png";

$px->setImage($img);

$px->zoom=1;
$px->span=1;

// 1) Pasar imagen a escala de grises
// $px->image2gray();
// 2) Resalta los bordes de la imagen
// $px->imageBorder();
// 3) Desenfoca la imagen usando el método gaussiano
// $px->imageGaussian();
// 4) Desenfoca la imagen con el método selectivo
// $px->imageSelective();
// 5) Un efecto para crear una imagen estilizada
// $px->imageMean();
// 6) Suaviza los bordes dentados de la imagen
// $px->imageSmooth();
// 7) Hace que la imagen se vea pixelada
// $px->imagePixelate();
// 8) Invierte los colores en la imagen
$px->imageNegate();


$px->pixMoore();
// $px->lineIdentifier();

$px->showImage();
$px->clearCache();