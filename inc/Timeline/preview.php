<?php
use inc\Autoloader;
use core\Session\Session;
use app\Table\Images\ImagesModel;
use app\Table\Images\Viewers\displayPostsImgsViewer;

if(isset($_POST['data']) && isset($_POST['dataimg']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $postid = $_POST['data'];
    $slugFirstImg = $_POST['dataimg'];
    $model = new ImagesModel();
    $valid = true;
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * SECURE CHECK
     */
    if(!Session::getInstance()->checkValueInSession($postid, 'PostDisplayed'))
    {
        $valid = false;
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * GET IMG POST
     */
    if($valid)
    {
        $imgObj = $model->getImagesFromPostId($postid);
        $display = new displayPostsImgsViewer($imgObj);
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