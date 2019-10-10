<?php

include "pixpic.class.php";
$px=new Pixpic();
$img="./elephpant.png";
//Set the image
$px->setImage($img);
//set property zoom =1 original size span=1 not separated
$pro=$px->getProperty();
if(!$px->error()){
	$palete=$px->image2palete();
	echo "<p>Attributes Image:</p>";    
	echo "<p>Width:".$pro[0]."</p>";
    echo "<p>Height:".$pro[1]."</p>";
    echo "<p>Bits:".$pro["bits"]."</p>";
    echo "<p>Type:".$pro["mime"]."</p>";
   echo "<h1>Paleta</h1>";
   foreach($palete as $color)
	echo "<div style='background-color:#".$color.";width:20px;height:20px;float:left;margin-left:3px;margin-bottom:3px;border:1px solid'></div>";
}else{
	echo $px->error();
}


?>
