<?php

include "pixpic.class.php";
$px=new Pixpic();
$img="./emo.png";
//Set the image
$px->setImage($img);
//set property zoom =1 original size span=1 not separated
$px->zoom=3;
$px->span=2;
if(!$px->error()){
	echo $px->image2div();
}else{
	echo $px->error();
}


?>
