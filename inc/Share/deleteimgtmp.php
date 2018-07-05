<?php
use inc\Autoloader;
use app\App;
use core\Tmp\Tmp;

if(isset($_POST['imgName']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $tmp = new Tmp();
    $tmp->deleteTmpFile($_POST['imgName']);
}
else{
    //display forbiden;
}