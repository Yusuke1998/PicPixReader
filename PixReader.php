<?php 

require "src/Tools.php";
require "vendor/autoload.php";
ini_set('memory_limit', '1024M');
const white = 16777215;

class PixReader extends Pixpic {
    use Filtered, Helpers;

    public function p8()
    {
        $vecindad=[];
        for ($y = 0; $y < imagesy($this->rs); $y++){
            for ($x = 0; $x < imagesx($this->rs); $x++) {
                $px=[
                    ["P1/C"    =>  ["X"=>$x,"Y"=>$y]],
                    ["P2/N"    =>  ["X"=>$x-1,"Y"=>$y]],
                    ["P3/NE"   =>  ["X"=>$x-1,"Y"=>$y+1]],
                    ["P4/E"    =>  ["X"=>$x,"Y"=>$y+1]],
                    ["P5/SE"   =>  ["X"=>$x+1,"Y"=>$y+1]],
                    ["P6/S"    =>  ["X"=>$x+1,"Y"=>$y]],
                    ["P7/SO"   =>  ["X"=>$x+1,"Y"=>$y-1]],
                    ["P8/O"    =>  ["X"=>$x,"Y"=>$y-1]],
                    ["P9/NO"   =>  ["X"=>$x-1,"Y"=>$y-1]],
                ];

                array_push($vecindad, $px);
            }
        }

        dd($vecindad,true);
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



    public function Test()
    {
        return "nada!";
    }
}
