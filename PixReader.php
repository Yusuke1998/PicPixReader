<?php 
require_once "src/Conf.php";

class PixReader extends Pixpic {

    use Filtered, Helpers;

    public function lineSpac3()
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
                    // dd($current,1);
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
        for ($l=0; $l < 1; $l++)
        {
            for ($m=0; $m < $okLines[$l]['c']; $m++) { 
                imagesetpixel($this->rs,$okLines[$l]['x']+$m,$okLines[$l]['y'],black);
            }
        }
        dd($okLines,1);
    }

    public function searchY($labels,$current)
    {
        foreach ($labels as $key => $label) {
            if ($label['y']===$current['y']) {
                return $label['l'];
            }
        }
        return false;
    }





























































    public function lineSpace()
    {
        $candidatos=0;
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
        #se elmina la primera linea de pixeles identificada
        for ($l=0; $l < 1; $l++)
        {
            for ($m=0; $m < $okLines[$l]['c']; $m++) { 
                imagesetpixel($this->rs,$okLines[$l]['x']+$m,$okLines[$l]['y'],black);
            }
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
}