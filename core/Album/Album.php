<?php

namespace core\Album;

use core\Session\Session;

use app\Table\Images\ImagesModel;
use app\Table\Images\Album\displayAlbumByMonth;

use core\Date\Date;


class Album extends AppAlbum
{
    protected $user;
    protected $myself;


    public function __construct($user = false)
    {
        $this->session = Session::getInstance();

        $this->user = $user;
        $this->myself = Session::getInstance()->read('auth');
    }

    //#todo attention ici les images sont get directement depuis la table image et non depuis la table post, ce qui retire le lien entre post et images, je dis ca au cas ou c'est utile pour plus tard
    //#todo fonction temporaire, affiche jusqu'a
    /****************************************************************************\
     *                          MY PROFILE PART                                 *
    \****************************************************************************/
    public function getMyAlbumLastMonth($datebegin = 'this', $nbmonth = 2)
    {
        //creation du date array
        $coreDate =     new Date();
        //creer le tableau associatif de dates month, year
        if($monthbegin = 'this')
        {
            $date = date('m Y');
        }
        else{
            $date = $datebegin;
        }
        $datesArray = $coreDate->getPreviousMYarray($date, $nbmonth);
        
        //pour chaques enttrée moi/année du date array, get les photos
        $model = new ImagesModel();
        $content = '';
        foreach($datesArray as $assocMonthYear)
        {
            $imgsObj = $model->getImagesByMonthYear($assocMonthYear['month'], $assocMonthYear['year'], $this->myself->pk_iduser);
            $display = new displayAlbumByMonth($imgsObj, $assocMonthYear['month'], $assocMonthYear['year'], $this->myself);
            $content = $content . $display->show();
            //#todo pour la suppression/edition $this->updateImagesDisplayed($idsArray);
        }
        return $content;
    }
    /****************************************************************************\
     *                          SOMEONE ELESE PART                              *
    \****************************************************************************/
    //#todo finalement la seule chose qui change entre les deux c'est l'id de l'user appelé par getImagesByMonthYear, la question est: conserver deux fonctions pour la larté et la maniabilité ou tout mettre en unse pour delaister la classe
    public function getUserAlbumLastMonth($datebegin = 'this', $nbmonth = 2)
    {
        //creation du date array
        $coreDate =     new Date();
        //creer le tableau associatif de dates month, year
        if($monthbegin = 'this')
        {
            $date = date('m Y');
        }
        else{
            $date = $datebegin;
        }
        $datesArray = $coreDate->getPreviousMYarray($date, $nbmonth);

        //pour chaques enttrée moi/année du date array, get les photos
        $model = new ImagesModel();
        $content = '';
        foreach($datesArray as $assocMonthYear)
        {
            $imgsObj = $model->getImagesByMonthYear($assocMonthYear['month'], $assocMonthYear['year'], $this->user->pk_iduser);
            $display = new displayAlbumByMonth($imgsObj, $assocMonthYear['month'], $assocMonthYear['year'], $this->user);
            $content = $content . $display->show();
            //#todo pour la suppression/edition $this->updateImagesDisplayed($idsArray);
        }
        return $content;
    }

}