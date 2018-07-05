<?php


namespace app\Table\Images\Album;

use core\Session\Session;
use app\Table\AppDisplay;
use app\Table\Images\Images\ImageForAlbum\displayImageForAlbum;

class displayAlbumByMonth extends AppDisplay
{
    private $imgsObj;
    private $month;
    private $year;

    private $monthInLetter;

    public function __construct($imgsObj, $month, $year, $user = false){
        parent::__construct($user);

        $this->imgsObj  = $imgsObj;
        $this->month    = $month;
        $this->year     = $year;

        //transform month in traduction
        $corresMonth = (int) ltrim($this->month, '0');
        $monthName = $this->montharray[(int)$corresMonth];
        $this->monthInLetter = $this->monthTraduce->$monthName;
    }

    public function showMyHeader()
    {
        return '<div class="bloc-picture-header col-md-12">
                    <div class="infos-title col-md-12">
                        <div class="date">
                            '.$this->monthInLetter.' '. $this->year .'
                        </div>
                    </div>
                </div>';
    }

    public function showUserHeader()
    {
        return '<div class="bloc-picture-header col-md-12">
                    <div class="infos-title col-md-12">
                        <div class="date">
                            '.$this->monthInLetter.' '. $this->year .'
                        </div>
                    </div>
                </div>';
    }

    public function showMyBody()
    {
        $content        = '';
        $imgArraySize   = sizeof($this->imgsObj);
        for($index = 0; $index < $imgArraySize; $index++)
        {
            $display = new displayImageForAlbum($this->imgsObj[$index], $index);
            $content = $content . $display->show();
        }

        return '<div class="bloc-picture-body col-md-12">
                    '. $content .'
                </div>';
    }

    public function showUserBody()
    {
        $content        = '';
        $imgArraySize   = sizeof($this->imgsObj);
        for($index = 0; $index < $imgArraySize; $index++)
        {
            $display = new displayImageForAlbum($this->imgsObj[$index], $index, $this->userToDisplay);
            $content = $content . $display->show();
        }

        return '<div class="bloc-picture-body col-md-12">
                    '. $content .'
                </div>';
    }

    public function showMyAlbum()
    {
        return '<section class="bloc-picture col-md-12">
                    '. $this->showMyHeader() .'
                    '. $this->showMyBody() .'
                </section>';
    }

    public function showUserAlbum()
    {
        return '<section class="bloc-picture col-md-12">
                    '. $this->showUserHeader() .'
                    '. $this->showUserBody() .'
                </section>';
    }

    public function show()
    {
        if(Session::getInstance()->read('current-state')['state'] == 'owner')
        {
            return $this->showMyAlbum(); //#todo changer ca: degeulasse
        }
        if(Session::getInstance()->read('current-state')['state'] == 'viewer')
        {
            return $this->showUserAlbum(); //#todo changer ca: degeulass
        }
    }
}