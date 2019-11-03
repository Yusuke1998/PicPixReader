<?php 
require_once "src/Conf.php";

class PixReader extends Pixpic {

    use Filtered, Helpers;

    public function Test()
    {
        $count_clusters=0;
        $label=[];
        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {

                if ($x>0 && $y>0)
                {
                    $current    = ["x"=>$x, "y"=>$y, "h"=>$this->hexPixel($x,$y)]; #center
                    $top        = ["x"=>$x, "y"=>$y-1, "h"=>$this->hexPixel($x,$y-1)]; #top
                    $topne      = ["x"=>$x+1, "y"=>$y-1, "h"=>$this->hexPixel($x+1,$y-1)]; #top noreste
                    $right      = ["x"=>$x+1, "y"=>$y, "h"=>$this->hexPixel($x+1,$y)]; #right
                    $bottom     = ["x"=>$x, "y"=>$y+1, "h"=>$this->hexPixel($x,$y+1)]; #bottom
                    $left       = ["x"=>$x-1, "y"=>$y, "h"=>$this->hexPixel($x-1,$y)]; #left

                    if ($current['h'] === 'ffffff') {

                        if ($top['h']==='000000' && $left['h'] === '000000') {
                        #si arriba y a la izquierda es negro (nuevo cluster)

                            if ($topne['h'] === 'ffffff') {
                                array_push($label,  ['x'=>$x,'y'=>$y,'c'=>$this->find($topne,$label)]);
                            }else{
                                $count_clusters+=1;
                                array_push($label,  ['x'=>$x,'y'=>$y,'c'=>$count_clusters]);
                            }
                            
                        
                        }elseif($left['h'] === 'ffffff' && $top['h']==='000000') { 
                        #si izquierda es blanco
                            array_push($label,  ['x'=>$x,'y'=>$y,'c'=>$this->find($left,$label)]);
                        
                        }elseif ($top['h']==='ffffff' && $left['h'] === '000000') { 
                        #si arriba es blanco
                            array_push($label,  ['x'=>$x,'y'=>$y,'c'=>$this->find($top,$label)]);
                        
                        }else{ 
                        #si ambos son blancos
                            array_push($label,  ['x'=>$x,'y'=>$y,'c'=>$this->union($left,$top,$label)]);

                        }
                    }
                }
            }
        }
        // $this->showClusters($label);
        $this->paintClusters($label);
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
        $count_left=0;
        $count_top=0;
        $cluster_left=0;
        $cluster_top=0;

        foreach ($labels as $label) {

            if ($label['x'] == $left['x'] && $label['y'] == $left['y']) {
                $count_left +=1;
                $cluster_left   = $this->find($left,$labels);
            }

            if ($label['x'] == $top['x'] && $label['y'] == $top['y']) {
                $count_top  +=1;
                $cluster_top    = $this->find($top,$labels);
            }
        }
        
        if ($count_top > $count_left) {
            return $cluster_top;
        }else{
            return $cluster_left;
        }
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
                    imagesetpixel($this->rs,$label['x'],$label['y'],lightblue);
                    break;
                case 2:
                    imagesetpixel($this->rs,$label['x'],$label['y'],green);
                    break;
                case 3:
                    imagesetpixel($this->rs,$label['x'],$label['y'],yellow);
                    break;
                case 4:
                    imagesetpixel($this->rs,$label['x'],$label['y'],magenta);
                    break;
                case 5:
                    imagesetpixel($this->rs,$label['x'],$label['y'],blue);
                    break;
                default:
                    imagesetpixel($this->rs,$label['x'],$label['y'],red);
                    break;
            }
        }
    }

    public function last($labels)
    {
        $c=null;
        foreach ($labels as $label) {
            $c = $label['c'];
        }
        return $c;
    }
}