<?php
use inc\Autoloader;
use app\Displays\displayContact;

if(isset($_POST['datas']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $todisplay = $_POST['datas'];

    $display = new displayContact($todisplay);
    echo($display->show());
    exit();
}
else{
    echo('err');
}