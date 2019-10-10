<?php 

require 'PixReader.php';

$px = new PixReader;
$img = "img/vertical-white.png";
$px->setImage($img);

$px->zoom=1;
$px->span=1;

// echo "1) Pasar imagen a escala de grises <br>";
// $px->image2gray();
// echo "2) Resalta los bordes de la imagen <br>";
// $px->imageBorder();
// echo "3) Desenfoca la imagen usando el método gaussiano <br>";
// $px->imageGaussian();
// echo "4) Desenfoca la imagen con el método selectivo <br>";
// $px->imageSelective();
// echo "5) Un efecto para crear una imagen estilizada <br>";
// $px->imageMean();
// echo "6) Suaviza los bordes dentados de la imagen <br>";
// $px->imageSmooth();
// echo "7) Hace que la imagen se vea pixelada <br>";
// $px->imagePixelate();
// echo "8) Invierte los colores en la imagen <br>";
// $px->imageNegate();

$px->showImage();

$px->lineIdentifier();

$px->clearCache();