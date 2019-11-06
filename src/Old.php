<?php
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
                $xi = $pixel[$key]['x'];
                $yi = $pixel[$key]['y'];
                $inicial = false;
            }
            // Valores finales de 'x' y 'y'
            $xf = $pixel[$key]['x'];
            $yf = $pixel[$key]['y'];
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

    public function Test2()
    {
        $camino = [];
        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {

                $p1 = ["n"=>"p1","x"=>$x, "y"=>$y, "h"=>''];
                $p2 = ["n"=>"p2","x"=>$x - 1, "y"=>$y, "h"=>''];
                $p3 = ["n"=>"p3","x"=>$x - 1, "y"=>$y + 1, "h"=>''];
                $p4 = ["n"=>"p4","x"=>$x, "y"=>$y + 1, "h"=>''];
                $p5 = ["n"=>"p5","x"=>$x + 1, "y"=>$y + 1, "h"=>''];
                $p6 = ["n"=>"p6","x"=>$x + 1, "y"=>$y, "h"=>''];
                $p7 = ["n"=>"p7","x"=>$x + 1, "y"=>$y - 1, "h"=>''];
                $p8 = ["n"=>"p8","x"=>$x, "y"=>$y - 1, "h"=>''];
                $p9 = ["n"=>"p9","x"=>$x - 1, "y"=>$y - 1, "h"=>''];

                if ($this->positive($p1)&&$this->positive($p2)&&$this->positive($p3)&&
                    $this->positive($p4)&&$this->positive($p5)&&$this->positive($p6)&&
                    $this->positive($p7)&&$this->positive($p8))
                {
                    $p1["h"]=$this->hexPixel($x,$y);
                    $p2["h"]=$this->hexPixel($x-1,$y);
                    $p3["h"]=$this->hexPixel($x-1,$y+1);
                    $p4["h"]=$this->hexPixel($x,$y+1);
                    $p5["h"]=$this->hexPixel($x+1,$y+1);
                    $p6["h"]=$this->hexPixel($x+1,$y);
                    $p7["h"]=$this->hexPixel($x+1,$y-1);
                    $p8["h"]=$this->hexPixel($x,$y-1);
                    $p9["h"]=$this->hexPixel($x-1,$y-1);

                    switch ($p1) {
                        case $p1['h']=='ffffff'&&$p1['h']==$p2['h']:
                            array_push($camino,$p2);
                            break;
                        case $p1['h']=='ffffff'&&$p1['h']==$p3['h']:
                            array_push($camino,$p3);
                            break;
                        case $p1['h']=='ffffff'&&$p1['h']==$p4['h']:
                            array_push($camino,$p4);
                            break;
                        case $p1['h']=='ffffff'&&$p1['h']==$p5['h']:
                            array_push($camino,$p5);
                            break;
                        case $p1['h']=='ffffff'&&$p1['h']==$p6['h']:
                            array_push($camino,$p6);
                            break;
                        case $p1['h']=='ffffff'&&$p1['h']==$p7['h']:
                            array_push($camino,$p7);
                            break;
                        case $p1['h']=='ffffff'&&$p1['h']==$p8['h']:
                            array_push($camino,$p8);
                            break;
                        case $p1['h']=='ffffff'&&$p1['h']==$p9['h']:
                            array_push($camino,$p9);
                            break;
                    }
                }
            }
        }
        dd($camino,true);
    }

    public function etiquetaVerdadera($historial,$s)
    {
        while ($historial[$s]<0) { // Es negativo, no es la etiqueta verdadera
            $s = $historial[$s];  // Tomo la etiqueta equivalente
        }
        return $historial[$s]; // Primer etiqueta equivalente positiva -> verdadera
    }

    public function etiqueta_falsa($sitio, $historial, $sup, $izq)
    {
        if($sitio!='ffffff'){ // Si el sitio esta desocupado, no hago nada
            $sup_v = $etiquetaVerdadera($historial, $sup); // Busco las etiquetas
            $izq_v = $etiquetaVerdadera($historial, $izq); // verdaderas de ambos
            if ($sup_v==$izq_v){
                $sitio = $sup_v; // Si tienen la misma etiqueta verdadera, safo
            }else{
                $historial[max($sup_v,$izq_v)] = -min($sup_v,$izq_v);
                $sitio = min($sup_v,$izq_v);
            } // La etiqueta verdadera de mayor valor pasa a ser falsa; son equivalentes
        }

    }

    public function actualizar($sitio, $historial, $s, $frag)
    {
        if ($sitio['h']=='ffffff'){ // Si el sitio esta desocupado, no hago nada
            if ($s!=0){
            $sitio = $this->etiquetaVerdadera($historial, $s);
            }else{
                // Creo nueva etiqueta, actualizo maxima etiqueta (frag)
            }
        }
    }

    public function Test3()
    {
        $cluster = [];
        $cluster_counter = 0;

        for ($y = 0; $y < imagesy($this->rs)-1; $y++){
            for ($x = 0; $x < imagesx($this->rs)-1; $x++) {
                if ($x>0 && $y>0)
                {
                    $current    = ["x"=>$x, "y"=>$y, "h"=>$this->hexPixel($x,$y)]; #center
                    $top        = ["x"=>$x, "y"=>$y - 1, "h"=>$this->hexPixel($x,$y-1)]; #top
                    $right      = ["x"=>$x + 1, "y"=>$y, "h"=>$this->hexPixel($x+1,$y)]; #right
                    $bottom     = ["x"=>$x, "y"=>$y + 1, "h"=>$this->hexPixel($x,$y+1)]; #bottom
                    $left       = ["x"=>$x - 1, "y"=>$y, "h"=>$this->hexPixel($x-1,$y)]; #left

                    if ($current['h'] === 'ffffff') #El pixel actual esta ocupado (es blanco)
                    {
                        if ($top['h'] === '000000' && $left['h'] === '000000') { #arriba y a la izquierda es negro

                        }else{
                            
                            if ($left['h'] === 'ffffff' && $top['h'] === '000000') #arriba es negro y a la izquierda es blanco
                            {


                            }

                            if ($top['h'] === 'ffffff' && $left['h'] === '000000') #arriba es blanco y a la izquierda es negro
                            {

                                
                            }
                        }
                        

                        if ($top['h'] === 'ffffff' && $left['h'] === 'ffffff') #arriba y a la izquierda es blanco
                        { 


                        }
                    }
                }
            }
        }

        dd($cluster,1);
    }


        public function ClusteringOld() #Aun le falta, tiene fallas
    {
        $count_clusters=0;
        $labels=[];
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
                        #si arriba y a la izquierda es negro (posible nuevo cluster)

                            if ($topne['h'] === 'ffffff') { #escalera de un pixel, si arriba al noreste es blanco, se pertece a ese cluster
                                array_push($labels,  ['x'=>$x,'y'=>$y,'c'=>$this->find($topne,$labels)]);
                            }else{ #nuevo cluster
                                $count_clusters+=1;
                                array_push($labels,  ['x'=>$x,'y'=>$y,'c'=>$count_clusters]);
                            }
                            
                        
                        }elseif($left['h'] === 'ffffff' && $top['h']==='000000') { 
                        #si izquierda es blanco
                            array_push($labels,  ['x'=>$x,'y'=>$y,'c'=>$this->find($left,$labels)]);
                        
                        }elseif ($top['h']==='ffffff' && $left['h'] === '000000') { 
                        #si arriba es blanco
                            array_push($labels,  ['x'=>$x,'y'=>$y,'c'=>$this->find($top,$labels)]);
                        
                        }else{ 
                        #si ambos son blancos
                            array_push($labels,  ['x'=>$x,'y'=>$y,'c'=>$this->union($left,$top,$labels)]);

                        }
                    }
                }
            }
        }
        $this->paintClusters($labels);
    }

    public function union($left,$top,$labels)
    {
        $cLeft=0;$cTop=0;$clLeft=0;$clTop=0;

        foreach ($labels as $label) {

            if ($label['x'] == $left['x'] && $label['y'] == $left['y']) {
                $cLeft +=1;
                $clLeft = $this->find($left,$labels);
            }
            if ($label['x'] == $top['x'] && $label['y'] == $top['y']) {
                $cTop  +=1;
                $clTop  = $this->find($top,$labels);
            }
        }
        
        if ($cTop > $cLeft) {
            return $clTop;
        }else{
            return $clLeft;
        }
    }

?>