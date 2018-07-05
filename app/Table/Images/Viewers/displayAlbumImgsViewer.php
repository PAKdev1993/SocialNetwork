<?php

namespace app\Table\Images\Viewers;

use app\Table\AppDisplay;
use app\Table\Images\Images\ImageForViewer\displayImageForViewers;

class displayAlbumImgsViewer extends AppDisplay
{
    private $imgs;
    private $nbImagesTodisplay;

    private $slideContainerSize;
    private $slideSize;
    private $btContainerSize;
    private $indexToDisplay;
    private $startMarge;


    public function __construct($arrayImgObj, $indextodisplay)
    {
        parent::__construct();
        //#todo calcul bourrin de width a ameliorer
        $this->nbImagesTodisplay =  sizeof($arrayImgObj);
        $this->imgs =               $arrayImgObj;
        $this->slideContainerSize = $this->nbImagesTodisplay * 100 . '%';
        $this->slideSize =          100/$this->nbImagesTodisplay . '%';
        $this->btContainerSize =    $this->slideSize/2 . '%';
        $this->indexToDisplay =     $indextodisplay;
        $this->startMarge =         '-' . $indextodisplay * 100 . '%';
    }

    public function showSlides()
    {
        $html = '';
        for($i = 0; $i < $this->nbImagesTodisplay; $i++)
        {
            if($i == 0)
            {
                //$classActive
            }
            if($i == $this->indexToDisplay)
            {
                $display = new displayImageForViewers($this->imgs[$i]);
                $html = $html . '<div class="slide col-md-12 active" data-index="'. $i .'" style="width:'. $this->slideSize .'">
                                    <div class="picture-container">
                                       '. $display->show() .'
                                    </div>
                                </div>';
            }
            else{
                $display = new displayImageForViewers($this->imgs[$i]);
                $html = $html . '<div class="slide col-md-12" data-index="'. $i .'" style="width:'. $this->slideSize .'">
                                    <div class="picture-container">
                                       '. $display->show() .'
                                    </div>
                                </div>';
            }
        }
        return $html;
    }

    public function showBody()
    {
        if($this->nbImagesTodisplay == 1)
        {
            return '<div id="viewer-pic" class="slider-container col-md-12">                       
                        <div class="bt-container">
                            <div class="bt-container-left col-md-6">
                                <a role="button" class="bt-left hided col-md-12">
                                </a>
                            </div>
                            <div class="bt-container-right col-md-6">
                                <a role="button" class="bt-right hided col-md-12">
                                </a>
                            </div>
                        </div> 
                        <div class="slides-container col-md-12" style="width:'. $this->slideContainerSize .';margin-left:'. $this->startMarge .'">
                            '. $this->showSlides() .'
                        </div>                   
                    </div>
                    <div class="bt-close mobile">                      
                    </div>';
        }
        else{
            if($this->indexToDisplay == 0)
            {
                return '<div id="viewer-pic" class="slider-container col-md-12">                       
                            <div class="bt-container">
                                <div class="bt-container-left col-md-6">
                                    <a role="button" class="bt-left hided col-md-12">
                                    </a>
                                </div>
                                <div class="bt-container-right col-md-6">
                                    <a role="button" class="bt-right col-md-12">
                                    </a>
                                </div>
                            </div> 
                            <div class="slides-container col-md-12" style="width:'. $this->slideContainerSize .';margin-left:'. $this->startMarge .'"">
                                '. $this->showSlides() .'
                            </div>                   
                        </div>
                        <div class="bt-close mobile">                      
                        </div>';
            }
            else{
                return '<div id="viewer-pic" class="slider-container col-md-12">                       
                            <div class="bt-container">
                                <div class="bt-container-left col-md-6">
                                    <a role="button" class="bt-left col-md-12">
                                    </a>
                                </div>
                                <div class="bt-container-right col-md-6">
                                    <a role="button" class="bt-right col-md-12">
                                    </a>
                                </div>
                            </div> 
                            <div class="slides-container col-md-12" style="width:'. $this->slideContainerSize .';margin-left:'. $this->startMarge .'"">
                                '. $this->showSlides() .'
                            </div>                   
                        </div>
                        <div class="bt-close mobile">                      
                        </div>';
            }

        }

    }

    public function show()
    {
        return $this->showBody();
    }
}