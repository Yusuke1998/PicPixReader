<?php 

require 'PixReader.php';

$px = new PixReader;
$img = "img/saco.png";

$px->setImage($img);

$px->zoom=3;
$px->span=1;

// 1) Pasar imagen a escala de grises
$px->image2gray();
// 2) Resalta los bordes de la imagen
// $px->imageBorder();
// 3) Desenfoca la imagen usando el método gaussiano
// $px->imageGaussian();
// 4) Desenfoca la imagen con el método selectivo
// $px->imageSelective();
// 5) Utiliza eliminación media para lograr un efecto "superficial"
// $px->imageMean();
// 6) Suaviza los bordes dentados de la imagen
// $px->imageSmooth();
// 7) Hace que la imagen se vea pixelada
// $px->imagePixelate();
// 8) Invierte los colores en la imagen
// $px->imageNegate();
// 9) Cambia el brillo de la imagen
// $px->imageBrightnss();
// 10) Cambia el contraste de la imagen
// $px->imageContrast();
// 11) Asignas colores, en orden rojo, verde, azul y alfa
// $px->imageColorize(0,0,0);

$px->paintPixel(white,0,0);

// $px->squelettisation();

$px->Test2();

$px->showImage();

// $px->saveImage();

$px->clearCache();