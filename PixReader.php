<?php 
require_once "src/Conf.php";

class PixReader extends Pixpic {

    use Filtered, Helpers;

    public function find()
    {

    }

    public function union($left,$top)
    {

    }

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
                    $right      = ["x"=>$x+1, "y"=>$y, "h"=>$this->hexPixel($x+1,$y)]; #right
                    $bottom     = ["x"=>$x, "y"=>$y+1, "h"=>$this->hexPixel($x,$y+1)]; #bottom
                    $left       = ["x"=>$x-1, "y"=>$y, "h"=>$this->hexPixel($x-1,$y)]; #left

                    if ($current['h'] === 'ffffff') {
                        
                        if ($top['h']==='000000' && $left['h'] === '000000') {

                            $count_clusters+=1;
                            
                            array_push($label, ['x'=>$x,'y'=>$y,'c'=>$count_clusters]);
                        
                        }elseif($top['h']==='000000' && $left['h'] === 'ffffff') {
                        
                            // $label[] = $this->find($left);
                        
                        }elseif ($top['h']==='ffffff' && $left['h'] === '000000') {

                            
                            // $label[] = $this->find($top);
                        }else{
                            
                            // $this->union($left,$top);
                        
                        }
                    }

                }
            }
        }
        dd($label);
        dd($count_clusters);
    }
}