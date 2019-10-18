<?php 

require "tools/dd.php";
require "vendor/autoload.php";

class PixReader extends Pixpic {

	private function imageArrPixel()
    {
    	$pixel=[];
    	if($this->determineProper()){
            for ($i = 0; $i < imagesy($this->rs); $i++){
                for ($j = 0; $j < imagesx($this->rs); $j++) {
                    $hex = $this->hexPixel($j,$i);
		            $pixel[]=array('X'=>$j,'Y'=>$i,'H'=>$hex);
                }
            }
            return $pixel;
        }else{
            return $this->error();
        }
    }

    private function hexPixel($j,$i)
    {
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
        return $he;
    }

    public function image2Info(){
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
                    $a[$he]=array("c"=>$he,"n"=>1,"p"=>0);
                }else{
                    $a[$he]["n"]+=1;
                    $a[$he]["p"]=round($a[$he]["n"]/$to,2);
                }
            }
        }
        return $a;  
    }

    public function paintPixel($objeto,$fondo,$linea)
    {
        $mM = $this->minMax();
        for ($i = 0; $i < imagesy($this->rs); $i++){
            for ($j = 0; $j < imagesx($this->rs); $j++) {
                $hex = $this->hexPixel($j,$i);
                if ($hex == $mM['min']['c']) {
                    imagesetpixel($this->rs,$j,$i,$objeto);
                }elseif ($hex == $mM['max']['c']) {
                    imagesetpixel($this->rs,$j,$i,$fondo);
                }else{
                    imagesetpixel($this->rs,$j,$i,$linea);
                }
            }
        }
    }

    public function minMax()
    {
        $imgInf = $this->image2Info();
        $a = array_filter($imgInf,function($a){
            if($a['p'] > 0){
                return $a;
            }
        });
        return array('min' => min($a),'max' => max($a));
    }

    public function p8()
    {

    }

    public function pixTest()
    {
        for ($y = 0; $y < imagesy($this->rs); $y++){
            for ($x = 0; $x < imagesx($this->rs); $x++) {
                $px=[
                    ["NO"   =>  [
                                    "X"=>$x-1,
                                    "Y"=>$y-1
                                ]
                    ],
                    ["N"    =>  [
                                    "X"=>$x-1,
                                    "Y"=>$y
                                ]
                    ],
                    ["NE"   =>  [
                                    "X"=>$x-1,
                                    "Y"=>$y+1
                                ]
                    ],
                    ["O"    =>  [
                                    "X"=>$x,
                                    "Y"=>$y-1
                                ]
                    ],
                    ["C"    =>  [
                                    "X"=>$x,
                                    "Y"=>$y
                                ]
                    ],
                    ["E"    =>  [
                                    "X"=>$x,
                                    "Y"=>$y+1
                                ]
                    ],
                    ["SO"   =>  [
                                    "X"=>$x+1,
                                    "Y"=>$y-1
                                ]
                    ],
                    ["S"    =>  [
                                    "X"=>$x+1,
                                    "Y"=>$y
                                ]
                    ],
                    ["SE"   =>  [
                                    "X"=>$x+1,
                                    "Y"=>$y+1
                                ]
                    ],
                ];
            }
        }

        dd($px);
        
        /**
        // 4 vecinos verticales y horizontales, se representan por N4(P).
        [$p['X'],$p['Y']-1] #(x,y-1)
        [$p['X'],$p['Y']+1] #(x,y+1)
        [$p['X']-1,$p['Y']] #(x-1,y)
        [$p['X']+1,$p['Y']] #(x+1,y)
         
        // 4 vecinos diagonales, son representados por ND(P)
        [$p['X']-1,$p['Y']-1] #(x-1,y-1)
        [$p['X']-1,$p['Y']+1] #(x-1,y+1)
        [$p['X']+1,$p['Y']-1] #(x+1,y-1)
        [$p['X']+1,$p['Y']+1] #(x+1,y+1)
        **/

    }

    public function lineIdentifier(){
        $pixel = $this->imageArrPixel();

        $forma = "No esta definida!";
        $inicial = true; #condicional
        $p = 0; #Pendiente
        $b = 0; #Punto de corte en 'y'
        // De izquierda a derecha, pixel a pixel:
        foreach ($pixel as $key => $value) {
            if ($inicial) {
            // Valores iniciales de 'x' y 'y'
                $xi = $pixel[$key]['X'];
                $yi = $pixel[$key]['Y'];
                $inicial = false;
            }
            // Valores finales de 'x' y 'y'
            $xf = $pixel[$key]['X'];
            $yf = $pixel[$key]['Y'];
        }
        // Diferenciales
        $dx = $xf-$xi;
        $dy = $yf-$yi;
        if (abs($dx) >= abs($dy)) {$longutud = abs($dx);}else{$longutud = abs($dy);}
        $incr_x = $dx/$longutud;
        $incr_y = $dy/$longutud;

        if ($xi!=$xf) { #Recta horizontal
            // Pendiente
            $p = ($xf-$xi/$yf-$yi);
            // Punto de corte 'y'
            $b = $yi - ($p*$xi);

            echo "Punto de corte $b<hr>";
            echo "Pendiente $p<hr>";

            if ($dy==0){ #Recta horizontal
                $forma = "Recta horizontal";
            }else{ #Recta creciente o decreciente
                if ($p == 1) { #Recta de 45°
                    $forma = "Recta de 45°";
                }elseif (abs($yi - $yf) < abs($xi - $xf)) { #recta con mas puntos en x
                    $forma = "Recta con mas puntos en 'x'.";
                }else{ #Recta con mas puntos en y
                    $forma = "Recta con mas puntos en 'y'.";
                }
                if ($dx>0 || $dy>0) {
                    $forma.=" creciente.";
                }else{
                    $forma.=" decreciente.";
                }
            }
        }else{ #Recta vertical
            $forma = "Recta vertical";
        }
        echo "Valor inicial de X:$xi y de Y : $yi<br>";
        echo "Valor final de X:$xf y de Y : $yf<br>";
        echo "Valor de longitud de linea : $longutud<br>";
        echo "Valor de pendiente : $p<br>";
        echo "Valor de incremento en X : $incr_x<br>";
        echo "Valor de incremento en Y : $incr_y<br>";
        echo "Valor del punto de corte en 'y' : $b<br>";
        echo "Valor de DX : $dx<br>";
        echo "Valor de DY : $dy<br>";
        echo "Forma: $forma";
    }

    public function showImage()
    {
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
                    $a.="px' title='(Y=$i - X=$j - #$he)'></div>";
                    $he="";
                }
            }
            if($this->zoom>1){
                if ($this->span>1){
                    echo "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->zoom+$this->span)."px;width:".imagesx($this->rs)*($this->zoom+$this->span)."px;'>".$a."</div>";
                }else{
                    echo "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->zoom)."px;width:".imagesx($this->rs)*($this->zoom)."px;'>".$a."</div>";
                }
            }else{
                if ($this->span>1){
                    echo "<div class='imgPixpic' style='height:".imagesy($this->rs)*($this->span+1)."px;width:".imagesx($this->rs)*($this->span+1)."px;'>".$a."</div>";
                }else{
                    echo "<div class='imgPixpic' style='height:".imagesy($this->rs)."px;width:".imagesx($this->rs)."px;'>".$a."</div>";
                }
            }
        }else{
            return $this->error();
        }
    }

    public function image2gray(){

        imagefilter($this->rs,IMG_FILTER_GRAYSCALE);
    }

    public function imageBorder(){

        imagefilter($this->rs,IMG_FILTER_EDGEDETECT);
    }

    public function imageGaussian(){

        imagefilter($this->rs,IMG_FILTER_GAUSSIAN_BLUR);
    }

    public function imageSelective(){

        imagefilter($this->rs,IMG_FILTER_SELECTIVE_BLUR);
    }

    public function imageSmooth($arg1=0){

        imagefilter($this->rs,IMG_FILTER_SMOOTH,$arg1);
    }

    public function imagePixelate($arg1=0){

        imagefilter($this->rs,IMG_FILTER_PIXELATE,$arg1);
    }

    public function imageBrightnss($arg1=0){

        imagefilter($this->rs,IMG_FILTER_BRIGHTNESS,$arg1);
    }

    public function imageContrast($arg1=0){

        imagefilter($this->rs,IMG_FILTER_CONTRAST,$arg1);
    }

    public function imageColorize($arg1=0,$arg2=0,$arg3=0,$arg4=0){

        imagefilter($this->rs,IMG_FILTER_COLORIZE,$arg1,$arg2,$arg3,$arg4);
    }

    public function imageMean(){

        imagefilter($this->rs,IMG_FILTER_MEAN_REMOVAL);
    }

    public function imageNegate(){

        imagefilter($this->rs,IMG_FILTER_NEGATE);
    }

    public function saveImage($name='img_',$path = 'img/',$ext = 'png')
    {
        $ruta = $path.$name.time().".$ext";
        return imagegd2($this->rs,$ruta);
    }

    public function clearCache()
    {
        return imagedestroy($this->rs);
    } 
}







#N4(P) -> (x+1,y),(x-1,y),(x,y+1),(x,y-1)
#ND(p) -> (x+1,y+1),(x+1,y-1),(x-1,y+1),(x-1,y-1)
#N8(p) -> ND(P)+N4(P)

// 4 vecinos verticales y horizontales, se representan por N4(P).
#(x,y-1) [$p['X'],$p['Y']-1]
#(x,y+1) [$p['X'],$p['Y']+1]
#(x-1,y) [$p['X']-1,$p['Y']]
#(x+1,y) [$p['X']+1,$p['Y']]
 
// 4 vecinos diagonales, son representados por ND(P)
#(x-1,y-1) [$p['X']-1,$p['Y']-1]
#(x-1,y+1) [$p['X']-1,$p['Y']+1]
#(x+1,y-1) [$p['X']+1,$p['Y']-1]
#(x+1,y+1) [$p['X']+1,$p['Y']+1]


// $mM = $this->minMax();
// echo $this->image2siluet($mM['min']['c']);
