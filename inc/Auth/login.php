<?php
require_once '../Autoloader.php';

use inc\Autoloader;
use app\App;
use core\Session\Session;

Autoloader::register();

if(isset($_POST['email']) && isset($_POST['pwd']))
{
    require_once '../Autoloader.php';
    Autoloader::register();
    
    $session = Session::getInstance();
    $result = App::getAuth()->login($_POST['email'], $_POST['pwd'], $_POST['rememberme']);

    if($result == "logged")
    {
        $session->setFlash('success', "vous etes maintenant connectés");
        echo('logged');
        exit();
    }
    if($result == "error login")
    {
        echo('user issue');
        exit();
    }
    if($result == "error confirm")
    {
        echo('confirm issue');
        exit();
    }
}
echo('err');
exit();