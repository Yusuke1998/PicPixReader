<?php 

trait Filtered
{
	// Filtros
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
    // Fin Filtros

}

trait Helpers
{
    public function searchY($labels,$current)
    {
        foreach ($labels as $key => $label) {
            if ($label['y']===$current['y']) {
                return $label['l'];
            }
        }
        return false;
    }

    public function searchLeft($labels,$current)
    {
        foreach ($labels as $label) {
            if ($label['y'] === $current['y'] && $label['x'] === $current['x']) {
                return $label['l'];
            }
        }
        return false;
    }

    public function find($position,$labels)
    {
        foreach ($labels as $label) {
            if ($position['x'] == $label['x'] && $position['y'] == $label['y']) {
                return $label['c'];
            }
        }
    }

    public function union($left,$top,$labels)
    {
        $cLeft=0;$cTop=0;$clLeft=0;$clTop=0;
        foreach ($labels as $label) {
            if ($label['x'] == $left['x'] && $label['y'] == $left['y']) {
                $clLeft = $this->find($left,$labels);
            }
            if ($label['x'] == $top['x'] && $label['y'] == $top['y']) {
                $clTop  = $this->find($top,$labels);
            }
        }
        return min($clLeft,$clTop);
    }

    public function showClusters($labels)
    {
        foreach ($labels as $label) {
            echo "x-".$label['x']." y-".$label['y']." c-".$label['c']."<br>";
        }
    }

    public function paintClusters($labels)
    {
        foreach ($labels as $label) {
            switch ($label['c']) {
                case 1:
                    imagesetpixel($this->rs,$label['x'],$label['y'],blue2);
                    break;
                case 2:
                    imagesetpixel($this->rs,$label['x'],$label['y'],green);
                    break;
                case 3:
                    imagesetpixel($this->rs,$label['x'],$label['y'],yellow2);
                    break;
                case 4:
                    imagesetpixel($this->rs,$label['x'],$label['y'],magenta);
                    break;
                case 5:
                    imagesetpixel($this->rs,$label['x'],$label['y'],blue3);
                    break;
                case 6:
                    imagesetpixel($this->rs,$label['x'],$label['y'],green2);
                    break;
                case 7:
                    imagesetpixel($this->rs,$label['x'],$label['y'],red3);
                    break;
                case 8:
                    imagesetpixel($this->rs,$label['x'],$label['y'],gray);
                    break;
                case 9:
                    imagesetpixel($this->rs,$label['x'],$label['y'],green3);
                    break;
                case 10:
                    imagesetpixel($this->rs,$label['x'],$label['y'],gray3);
                    break;
                case 11:
                    imagesetpixel($this->rs,$label['x'],$label['y'],red2);
                    break;
                case 12:
                    imagesetpixel($this->rs,$label['x'],$label['y'],blue);
                    break;
                case 13:
                    imagesetpixel($this->rs,$label['x'],$label['y'],cian);
                    break;
                case 14:
                    imagesetpixel($this->rs,$label['x'],$label['y'],gray2);
                    break;
                case 15:
                    imagesetpixel($this->rs,$label['x'],$label['y'],magenta2);
                    break;
                default:
                    imagesetpixel($this->rs,$label['x'],$label['y'],red);
                    break;
            }
        }
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
                    $a.="px' title='(X=$j - Y=$i - #$he)'></div>";
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

    public function hexPixel($x,$y)
    {
        $pixelxy = imagecolorat($this->rs, $x, $y);
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

    public function intPixel($x,$y)
    {
        return imagecolorat($this->rs, $x, $y);
    }

    public function imageArrPixel()
    {
    	if($this->determineProper()){
            for ($y = 0; $y < imagesy($this->rs); $y++){
                for ($x = 0; $x < imagesx($this->rs); $x++) {
                    $hex = $this->hexPixel($x,$y);
		            $pixel[]=array('x'=>$x,'y'=>$y,'h'=>$hex);
                }
            }
            return $pixel;
        }else{
            return $this->error();
        }
    }

    public function colBin($p)
    {
        $b = ($this->hexPixel($p['x'],$p['y']) === 'ffffff')?1:0;
        return $b;
    }

    public function positive($p)
    {
        if ($p['x'] > 0 && $p['y'] > 0) return true;
        return false;
    }

    public function iterSquelettisation($re)
    {
        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {

                $p1 = ["x"=>$x, "y"=>$y];
                $p2 = ["x"=>$x - 1, "y"=>$y];
                $p3 = ["x"=>$x - 1, "y"=>$y + 1];
                $p4 = ["x"=>$x, "y"=>$y + 1];
                $p5 = ["x"=>$x + 1, "y"=>$y + 1];
                $p6 = ["x"=>$x + 1, "y"=>$y];
                $p7 = ["x"=>$x + 1, "y"=>$y - 1];
                $p8 = ["x"=>$x, "y"=>$y - 1];
                $p9 = ["x"=>$x - 1, "y"=>$y - 1];

                // Si tiene vecino positivo
                if ($this->positive($p1)&&$this->positive($p2)&&$this->positive($p3)&&
                    $this->positive($p4)&&$this->positive($p5)&&$this->positive($p6)&&
                    $this->positive($p7)&&$this->positive($p8))
                {
                    $A = ($this->colBin($p2) == 0 && $this->colBin($p3) == 1) + ($this->colBin($p3) == 0 && $this->colBin($p4) == 1) +
                     ($this->colBin($p4) == 0 && $this->colBin($p5) == 1) + ($this->colBin($p5) == 0 && $this->colBin($p6) == 1) +
                     ($this->colBin($p6) == 0 && $this->colBin($p7) == 1) + ($this->colBin($p7) == 0 && $this->colBin($p8) == 1) +
                     ($this->colBin($p8) == 0 && $this->colBin($p9) == 1) + ($this->colBin($p9) == 0 && $this->colBin($p2) == 1);
                    $B = $this->colBin($p2) + $this->colBin($p3) + $this->colBin($p4) + $this->colBin($p5) + $this->colBin($p6) + $this->colBin($p7) + $this->colBin($p8) + $this->colBin($p9);
                    $m1 = $re == 0 ? ($this->colBin($p2) * $this->colBin($p4) * $this->colBin($p6)) : ($this->colBin($p2) * $this->colBin($p4) * $this->colBin($p8));
                    $m2 = $re == 0 ? ($this->colBin($p4) * $this->colBin($p6) * $this->colBin($p8)) : ($this->colBin($p2) * $this->colBin($p6) * $this->colBin($p8));
                    if ($A == 1 && ($B >= 2 && $B <= 6) && $m1 == 0 && $m2 == 0) {
                        imagesetpixel($this->rs,$x,$y,0);
                    }
                }
            }
        }
    }

    public function squelettisation()
    {
        $this->iterSquelettisation(0);
        $this->iterSquelettisation(1);
    }

    public function image2Info()
    {
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

    public function paintPixel($objeto='16777215',$fondo='0',$linea='255')
    {
        $mM = $this->minMax();
        for ($y = 0; $y < imagesy($this->rs); $y++){
            for ($x = 0; $x < imagesx($this->rs); $x++) {
                $hex = $this->hexPixel($x,$y);
                if ($hex === $mM['min']['c']) {
                    imagesetpixel($this->rs,$x,$y,$objeto);
                }elseif ($hex === $mM['max']['c']) {
                    imagesetpixel($this->rs,$x,$y,$fondo);
                }else{
                    imagesetpixel($this->rs,$x,$y,$linea);
                }
            }
        }
    }

    public function saveImage($name='img_',$path = '.\img\\',$ext = '.png')
    {
        $FileName = $path.date('d-m-Y').$ext;
        return imagepng($this->rs,$FileName);
    }

    public function clearCache()
    {
        return imagedestroy($this->rs);
    }
}

function dd($var, $pretty = false)
{
    $backtrace = debug_backtrace();
    echo "\n<pre>\n";
    if (isset($backtrace[0]['file'])) {
        echo $backtrace[0]['file'] . "\n\n";
    }
    echo "Type: " . gettype($var) . "\n";
    echo "Time: " . date('c') . "\n";
    echo "---------------------------------\n\n";
    ($pretty) ? print_r($var) : var_dump($var);
    echo "</pre>\n";
    die;
}