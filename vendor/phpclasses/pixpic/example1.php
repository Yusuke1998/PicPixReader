<style>
    .imgPixpic{display:block;margin-bottom:20px;}
</style>
<?php

include "pixpic.class.php";
$px=new Pixpic();
$img="./elephpant.png";
//Set the image
$px->setImage($img);
//set property zoom =1 original size span=1 not separated
$px->zoom=1;
$px->span=1;
$pro=$px->getProperty();
if(!$px->error()){
	echo $px->image2div();
    echo $px->image2siluet("000000");
    echo $px->image2alpha("000000");
	echo "<p>Attributes Image:</p>";    
	echo "<p>Width:".$pro[0]."</p>";
    echo "<p>Height:".$pro[1]."</p>";
    echo "<p>Bits:".$pro["bits"]."</p>";
    echo "<p>Type:".$pro["mime"]."</p>";
}else{
	echo $px->error();
}


?>
