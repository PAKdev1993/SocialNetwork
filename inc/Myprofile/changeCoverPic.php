<?php
use inc\Autoloader;
use app\App;
use core\Session\Session;
use core\Files\Images\Images;
use core\Tmp\Tmp;
use app\Table\UserModel\UserModel;
use app\Table\UserModel\displayUser;

if(isset($_POST['picName']))
{
    require_once '../Autoloader.php';
    Autoloader::register();
    
    //upload image
    $images = new Images();
    $images->uploadCoverPic();

    //delete tmp
    $tmp = new Tmp();
    $tmp->deleteTmpFolder();

    //save cover
    $model =  new UserModel(App::getDatabase());
    $model->saveCoverPic($_POST['picName']);
    Session::getInstance()->read('auth')->background_cover = $_POST['picName'];

    //display new pic
    $display = new displayUser(Session::getInstance()->read('auth'));
    $content = $display->showCoverPic();
    echo($content);
    exit();
}
else{
    echo('err');
    exit();
}