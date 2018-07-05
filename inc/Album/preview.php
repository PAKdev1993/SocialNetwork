<?php
use inc\Autoloader;
use core\Session\Session;
use app\Table\Images\ImagesModel;
use app\Table\Images\Viewers\displayAlbumImgsViewer;

if(isset($_POST['datadate']) && isset($_POST['dataid']) && isset($_POST['dataindex']) && isset($_POST['dataimgid']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $userid =       $_POST['dataid'];
    $indextodisplay=$_POST['dataindex'];
    $date          =$_POST['datadate'];
    $imgSlug =      $_POST['dataimgid'];
    $pieces =       explode('_', $_POST['datadate']);
    $month =        $pieces[0];
    $year =         $pieces[1];
    $model = new ImagesModel();
    $valid = true;
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * SECURE CHECK
     */
    if(!Session::getInstance()->checkValueInSession($imgSlug, 'ImgDisplayed'))
    {
        //$valid = false;
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * GET IMG POST
     */
    if($valid)
    {
        $imgObj = $model->getImagesByMonthYear($month, $year, $userid);
        $display = new displayAlbumImgsViewer($imgObj, $indextodisplay);
        echo $display->show();
        exit();
    }
    else{
        echo('err');
        exit();
    }
}
else{
    echo('err');
    exit();
}