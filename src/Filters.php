<?php 

trait Filters
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
