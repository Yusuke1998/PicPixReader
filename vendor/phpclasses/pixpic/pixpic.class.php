<?php
/* class pixpic by Miguel Hernandez Liebano (mhliebano@gmail.com)
 * 
 * This class allows you to draw a picture in the browser using 
 * div tag for each pixel image containing
 * 
 * property class
 *   zoom: allow resize the image
 *   span: allow separate each pixel into image
 * 
 * methods:
 *   privates:
 *     determineProper->(true,false): determine if the zoom and span value are correct
 *     res->(image resource): transform the string of image into image resource
 *   public:
 *     setImage($image)->(void): sets the image drawing
 *     getProperty->(array(sizeImage,typeImage): returns an array with the size and type properties of the selected image
	   image2div->(elements div): Draw a picture using div with the color of each pixel containing the image
       image2siluet($color)->(elements div):Div draws an image using only the color of the pixel may be specified in the argument
       image2alpha($color)->(elements div): Div draws an image whitout the color of the pixel may be specified in the argument
      image2Palete->(array: returns an array with hex color of the selected image
	  image2PaletePorc(associative array)->returns an associative array with hex color of the selected image, count of pixel for each type color and their porcen.
      error->(string): Error of the class, false if not error given.
 *     
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 */

class Pixpic{
    public $error;
    public $image;
    public $rs;
    public $imageProp;
    public $zoom;
    public $span;
    function __construct(){
        if (!extension_loaded('gd')) {
            $this->error="001-GD extension not installed";
        }else{
            $this->error=false;
            $this->imageProp=null;
            $this->image=null;
            $this->rs=null;
            $this->zoom=1;
            $this->span=1;
        }
    }
    public function determineProper(){
        if($this->zoom<1){
            $this->error="005-Zoom value wrong";
            return false;
        }else{
            if($this->span<1){
                $this->error="006-Span value wrong";
                return false;
            }
        }
        return true;
    }
    public function res(){
        if(file_exists($this->image)){
            $this->rs=imagecreatefromstring(file_get_contents($this->image));
            if ($this->rs==null)
                $this->error="003-Imagen not soported";
        }else{
            $this->error="008-File or Image not exist";
        }
        
    }
    public function setImage($image=null){
        if($image!=null){
            $this->image=$image;
            $this->res();           
        }else{
            $this->error="004-Not Image";
        }
    }
    public function getProperty(){
        if($this->image!=null){
            $this->imageProp= getimagesize($this->image);
            return $this->imageProp;
        }else{
            $this->error="002-Not Image";
        }
    }

    public function image2div(){
        $a=null;
        if($this->determineProper()){
            for ($i = 0; $i < imagesy($this->rs); $i++){
                for ($j = 0; $j < imagesx($this->rs); $j++) {
                    $pixelxy = imagecolorat($this->rs, $j, $i);
                    $rgb = imagecolorsforindex($this->rs, $pixelxy);
                    
                    $r=dechex($rgb["red"]);
                    $g=dechex($rgb["green"]);
                    $b=dechex($rgb["blue"]);
                    if(strlen($r)==1){
                        $he="0".$r;
                    }else{
                        $he=$r;
                    }
                    if(strlen($g)==1){
                        $he.="0".$g;
                    }else{
                        $he.=$g;
                    }
                    if(strlen($b)==1){
                        $he.="0".$b;
                    }else{
                        $he.=$b;
                    }
                    $a.= "<div style='position:static;float:left;width:".$this->zoom."px;height:".$this->zoom."px;background:#".$he.";top:".($j*$this->span*$this->zoom)."px;left:".($i*$this->span*$this->zoom)."px;margin-right:";
                    if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                    $a.="px;margin-bottom:";
                    if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                    $a.="px' title='$i-$j'></div>";
                    $he="";
                }
            }
            if($this->zoom>1){
                if ($this->span>1){
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->zoom+$this->span)."px;width:".imagesx($this->rs)*($this->zoom+$this->span)."px;'>".$a."</div>";
                }else{
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->zoom)."px;width:".imagesx($this->rs)*($this->zoom)."px;'>".$a."</div>";
                }
            }else{
                if ($this->span>1){
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->span+1)."px;width:".imagesx($this->rs)*($this->span+1)."px;'>".$a."</div>";
                }else{
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)."px;width:".imagesx($this->rs)."px;'>".$a."</div>";
                }
            }
        }else{
            return $this->error();
        }
    } 
    public function image2siluet($color){
        $a=null;
        $color=strtolower($color);
        if($this->determineProper()){
            for ($i = 0; $i < imagesy($this->rs); $i++){
                for ($j = 0; $j < imagesx($this->rs); $j++) {
                    $pixelxy = imagecolorat($this->rs, $j, $i);
                    $rgb = imagecolorsforindex($this->rs, $pixelxy);
                    
                    $r=dechex($rgb["red"]);
                    $g=dechex($rgb["green"]);
                    $b=dechex($rgb["blue"]);
                    if(strlen($r)==1){
                        $he="0".$r;
                    }else{
                        $he=$r;
                    }
                    if(strlen($g)==1){
                        $he.="0".$g;
                    }else{
                        $he.=$g;
                    }
                    if(strlen($b)==1){
                        $he.="0".$b;
                    }else{
                        $he.=$b;
                    }
                    if($color==$he){
                         $a.= "<div style='position:static;float:left;width:".$this->zoom."px;height:".$this->zoom."px;background:#".$he.";top:".($j*$this->span*$this->zoom)."px;left:".($i*$this->span*$this->zoom)."px;margin-right:";
                        if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                        $a.="px;margin-bottom:";
                        if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                        $a.="px' title='$i-$j'></div>";
                    }else{
                         $a.= "<div style='position:static;float:left;width:".$this->zoom."px;height:".$this->zoom."px;background:transparent;top:".($j*$this->span*$this->zoom)."px;left:".($i*$this->span*$this->zoom)."px;margin-right:";
                        if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                        $a.="px;margin-bottom:";
                        if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                        $a.="px' title='$i-$j'></div>";
                    }
                    $he="";
                }
            }
            if($this->zoom>1){
                if ($this->span>1){
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->zoom+$this->span)."px;width:".imagesx($this->rs)*($this->zoom+$this->span)."px;'>".$a."</div>";
                }else{
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->zoom)."px;width:".imagesx($this->rs)*($this->zoom)."px;'>".$a."</div>";
                }
            }else{
                if ($this->span>1){
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->span+1)."px;width:".imagesx($this->rs)*($this->span+1)."px;'>".$a."</div>";
                }else{
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)."px;width:".imagesx($this->rs)."px;'>".$a."</div>";
                }
            }
        }else{
            return $this->error();
        }
    }
    
    public function image2alpha($color){
        $a=null;
        $color=strtolower($color);
        if($this->determineProper()){
            for ($i = 0; $i < imagesy($this->rs); $i++){
                for ($j = 0; $j < imagesx($this->rs); $j++) {
                    $pixelxy = imagecolorat($this->rs, $j, $i);
                    $rgb = imagecolorsforindex($this->rs, $pixelxy);
                    
                    $r=dechex($rgb["red"]);
                    $g=dechex($rgb["green"]);
                    $b=dechex($rgb["blue"]);
                    if(strlen($r)==1){
                        $he="0".$r;
                    }else{
                        $he=$r;
                    }
                    if(strlen($g)==1){
                        $he.="0".$g;
                    }else{
                        $he.=$g;
                    }
                    if(strlen($b)==1){
                        $he.="0".$b;
                    }else{
                        $he.=$b;
                    }
                    if($color!=$he){
                         $a.= "<div style='position:static;float:left;width:".$this->zoom."px;height:".$this->zoom."px;background:#".$he.";top:".($j*$this->span*$this->zoom)."px;left:".($i*$this->span*$this->zoom)."px;margin-right:";
                        if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                        $a.="px;margin-bottom:";
                        if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                        $a.="px' title='$i-$j'></div>";
                    }else{
                         $a.= "<div style='position:static;float:left;width:".$this->zoom."px;height:".$this->zoom."px;background:transparent;top:".($j*$this->span*$this->zoom)."px;left:".($i*$this->span*$this->zoom)."px;margin-right:";
                        if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                        $a.="px;margin-bottom:";
                        if($this->span>1){$a.=$this->span;}else{$a.= "0";}
                        $a.="px' title='$i-$j'></div>";
                    }
                    $he="";
                }
            }
            if($this->zoom>1){
                if ($this->span>1){
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->zoom+$this->span)."px;width:".imagesx($this->rs)*($this->zoom+$this->span)."px;'>".$a."</div>";
                }else{
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->zoom)."px;width:".imagesx($this->rs)*($this->zoom)."px;'>".$a."</div>";
                }
            }else{
                if ($this->span>1){
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->span+1)."px;width:".imagesx($this->rs)*($this->span+1)."px;'>".$a."</div>";
                }else{
                    return "<div class='imgPixpic' style='height:".imagesy($this->rs)."px;width:".imagesx($this->rs)."px;'>".$a."</div>";
                }
            }
        }else{
            return $this->error();
        }
    }
     
    public function image2Palete(){
        $a=array();
        for ($i = 0; $i < imagesx($this->rs); $i++){
            for ($j = 0; $j < imagesy($this->rs); $j++) {
                $pixelxy = imagecolorat($this->rs, $j, $i);
                $rgb = imagecolorsforindex($this->rs, $pixelxy);
                $r=dechex($rgb["red"]);
                    $g=dechex($rgb["green"]);
                    $b=dechex($rgb["blue"]);
                    if(strlen($r)==1){
                        $he="0".$r;
                    }else{
                        $he=$r;
                    }
                    if(strlen($g)==1){
                        $he.="0".$g;
                    }else{
                        $he.=$g;
                    }
                    if(strlen($b)==1){
                        $he.="0".$b;
                    }else{
                        $he.=$b;
                    }
                if(!in_array($he,$a))               
                    $a[]=$he;
            }
        }
        return $a;  
    }
    public function image2PaletePorc(){
        $a=array();
        $x=imagesx($this->rs);$y=imagesy($this->rs);
        $to=$x*$y;
        for ($i = 0; $i < $x; $i++){
            for ($j = 0; $j < $y; $j++) {
                $pixelxy = imagecolorat($this->rs, $j, $i);
                $rgb = imagecolorsforindex($this->rs, $pixelxy);
                $r=dechex($rgb["red"]);
                    $g=dechex($rgb["green"]);
                    $b=dechex($rgb["blue"]);
                    if(strlen($r)==1){
                        $he="0".$r;
                    }else{
                        $he=$r;
                    }
                    if(strlen($g)==1){
                        $he.="0".$g;
                    }else{
                        $he.=$g;
                    }
                    if(strlen($b)==1){
                        $he.="0".$b;
                    }else{
                        $he.=$b;
                    }
                if(!array_key_exists($he,$a)){              
                    $a[$he]=array("n"=>1,"p"=>0);
                }else{
                    $a[$he]["n"]+=1;
                    $a[$he]["p"]=round($a[$he]["n"]/$to,2);
                }
            }
        }
        return $a;  
    }

    public function error(){
        return $this->error;    
    }

}

?>