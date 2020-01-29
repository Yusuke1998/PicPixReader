<?php 
require_once "src/Conf.php";

class PixReader extends Pixpic {

    use Filters, Helpers;

    public function lineSpace()
    {
        $candidatos=[]; #candidatos a ser la linea de tiempo
        $cLine=0; #cantidad de lineas
        $cColor=0; #cantidad de lineas
        $xLine = 0; #cantidad de pixeles de la linea
        $equals=[]; #lineas similares
        $lines=[]; #lineas validas
        $xLength = imagesx($this->rs);
        $okLines = []; #lineas con un tamaño mayor al 80 del ancho de la imagen
        define('xMax', ($xLength/100)*80); #porcentaje valido para linea (80%)
        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {
                if ($x>0 && $y>0)
                {
                    $current    = ["x"=>$x, "y"=>$y, "h"=>$this->hexPixel($x,$y)]; #center
                    $top        = ["x"=>$x, "y"=>$y-1, "h"=>$this->hexPixel($x,$y-1)]; #top
                    $right      = ["x"=>$x+1, "y"=>$y, "h"=>$this->hexPixel($x+1,$y)]; #right
                    $bottom     = ["x"=>$x, "y"=>$y+1, "h"=>$this->hexPixel($x,$y+1)]; #bottom
                    $left       = ["x"=>$x-1, "y"=>$y, "h"=>$this->hexPixel($x-1,$y)]; #left
                    
                    if ($current['h'] != '000000') #Si el px actual es blanco
                    {
                        #Se crea una linea
                        if ($top['h'] === '000000' && $left['h'] === '000000') #nueva linea
                        {
                            $cLine+=1;
                            array_push($lines, [
                                'x'=>$x,
                                'y'=>$y,
                                'l'=>$cLine,
                                'c'=>1
                            ]);
                        }else{
                            #Se pertenece a alguna linea
                            if ($left['h'] === 'ffffff' && $right['h'] === '000000') {
                                if ($this->searchLeft($lines,$left)!=0) {
                                    array_push($equals, [
                                        'x'=>$x,
                                        'y'=>$y,
                                        'l'=>$this->searchLeft($lines,$left)
                                    ]);
                                }else{
                                    array_push($equals, [
                                        'x'=>$x,
                                        'y'=>$y,
                                        'l'=>$this->searchLeft($equals,$left)
                                    ]);
                                }
                            #Es parte de una linea
                            }elseif ($left['h'] === 'ffffff' && $right['h'] === 'ffffff')
                            {
                                if ($this->searchLeft($lines,$left)!=0) {
                                    array_push($equals, [
                                        'x'=>$x,
                                        'y'=>$y,
                                        'l'=>$this->searchLeft($lines,$left)
                                    ]);
                                }else{
                                    array_push($equals, [
                                        'x'=>$x,
                                        'y'=>$y,
                                        'l'=>$this->searchLeft($equals,$left)
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        #contamos los pixeles por linea
        for ($i=0; $i < sizeof($lines); $i++)
        {
            for ($j=0; $j < sizeof($equals); $j++)
            {
                if ($equals[$j]['l'] === $lines[$i]['l']) {
                    $lines[$i]['c']+=1;
                }
            }
        }
        #iteramos y eliminamos las lineas menores
        for ($k=0; $k < sizeof($lines); $k++)
        {
            if ($lines[$k]['c'] > xMax)
            {
                array_push($okLines,$lines[$k]);
            }
        }
        $candidatos_of=[];
        $cLine = 0;
        #prueba para encontrar candidatos
        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {
                if ($x>0 && $y>=$okLines[0]['y'])
                {
                    $current    = ["x"=>$x, "y"=>$y, "h"=>$this->hexPixel($x,$y)]; #center
                    $top        = ["x"=>$x, "y"=>$y-1, "h"=>$this->hexPixel($x,$y-1)]; #top
                    $right      = ["x"=>$x+1, "y"=>$y, "h"=>$this->hexPixel($x+1,$y)]; #right
                    $bottom     = ["x"=>$x, "y"=>$y+1, "h"=>$this->hexPixel($x,$y+1)]; #bottom
                    $left       = ["x"=>$x-1, "y"=>$y, "h"=>$this->hexPixel($x-1,$y)]; #left
                    if ($current['h'] === 'ffffff') {

                        if ($left['h'] === '000000')
                        {
                            $cLine+=1;
                            array_push($candidatos, [
                                "x"=>$x, "y"=>$y, "l"=>$cLine, 'c'=>1
                            ]);

                        }else{
                            if ($this->searchY($candidatos,$current)!==null&&$this->searchY($candidatos,$current)>0) {
                                array_push($candidatos_of, [
                                    'x'=>$x,
                                    'y'=>$y,
                                    'l'=>$this->searchY($candidatos,$current)
                                ]);
                            }
                        }
                    }
                }
            }
        }
        for ($i=0; $i < sizeof($candidatos); $i++)
        {
            for ($j=0; $j < sizeof($candidatos_of); $j++)
            {
                if ($candidatos_of[$j]['l'] === $candidatos[$i]['l']) {
                    $candidatos[$i]['c']+=1;
                }
            }
        }
        $okLines=[];
        for ($k=0; $k < sizeof($candidatos); $k++)
        {
            if ($candidatos[$k]['c'] > xMax)
            {
                array_push($okLines,$candidatos[$k]);
            }
        }

        #se elmina la primera y la ultima linea de pixeles identificada
        $firstLine  = 0;
        $lastLine   = sizeof($okLines)-1;
        for ($m=0; $m < $okLines[$firstLine]['c']; $m++) { 
            imagesetpixel($this->rs,$okLines[$firstLine]['x']+$m,$okLines[$firstLine]['y'],black);
        }
        for ($m=0; $m < $okLines[$lastLine]['c']; $m++) { 
            imagesetpixel($this->rs,$okLines[$lastLine]['x']+$m,$okLines[$lastLine]['y'],black);
        }
    }

    public function Clustering()
    {
        $count_clusters=0;$labels=[];$equals=[];
        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {
                if ($x>0 && $y>0)
                {
                    $current    = ["x"=>$x, "y"=>$y, "h"=>$this->hexPixel($x,$y)]; #center
                    $top        = ["x"=>$x, "y"=>$y-1, "h"=>$this->hexPixel($x,$y-1)]; #top
                    $right      = ["x"=>$x+1, "y"=>$y, "h"=>$this->hexPixel($x+1,$y)]; #right
                    $bottom     = ["x"=>$x, "y"=>$y+1, "h"=>$this->hexPixel($x,$y+1)]; #bottom
                    $left       = ["x"=>$x-1, "y"=>$y, "h"=>$this->hexPixel($x-1,$y)]; #left
                    if ($current['h'] != '000000') {
                        if ($top['h']==='000000' && $left['h'] === '000000') {
                            $count_clusters+=1;
                            array_push($labels,  ['x'=>$x,'y'=>$y,'c'=>$count_clusters]);
                        }else{
                            if ($left['h'] === 'ffffff' && $top['h'] != 'ffffff') {
                                array_push($labels, ['x'=>$x,'y'=>$y,'c'=>$this->find($left,$labels)]);
                            }elseif ($top['h'] === 'ffffff' && $left['h'] != 'ffffff') {
                                array_push($labels, ['x'=>$x,'y'=>$y,'c'=>$this->find($top,$labels)]);
                            }elseif($top['h'] === 'ffffff' && $left['h'] === 'ffffff') {
                                if($this->find($top,$labels) !== $this->find($left,$labels)){
                                    array_push($equals, ['x'=>$x,'y'=>$y,'c'=>$this->union($left,$top,$labels),'ct'=>$this->find($top,$labels),'cl'=>$this->find($left,$labels)]);
                                    array_push($labels, ['x'=>$x,'y'=>$y,'c'=>$this->union($left,$top,$labels)]);
                                }else{
                                    array_push($labels, ['x'=>$x,'y'=>$y,'c'=>$this->find($left,$labels)]);
                                }
                            }
                        }
                    }
                }
            }
        }

        for ($i=0; $i < sizeof($labels); $i++) {
            foreach ($equals as $equal) {
                if ($labels[$i]['c'] !== $equal['c']) {
                    if ($labels[$i]['c'] === $equal['ct']) {
                        $labels[$i]['c']=$equal['c'];
                    }elseif ($labels[$i]['c'] === $equal['cl']) {
                        $labels[$i]['c']=$equal['c'];
                    }
                }
            }
        }
        $this->paintClusters($labels);
    }
}