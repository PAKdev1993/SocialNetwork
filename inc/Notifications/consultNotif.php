<?php
use inc\Autoloader;
use core\Session\Session;

use app\Table\Notifications\NotificationModel;

//#todo page similaire a askForAdd de My community, factoriser ou que sais-je
if(isset($_POST['id']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $valid = true;
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * SECURITY CONTROLS
     */
    $model = new NotificationModel();
    $notifAddresee = $model->getAddreseeIdFromNotifId($_POST['id']);

    if(Session::getInstance()->read('auth')->pk_iduser != $notifAddresee)
    {
        $valid = false;
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * BLOCK
     */
    if($valid)
    {
        $idnotif = $_POST['id'];
        $model->consultMyNotificationFromId($idnotif);
        echo('');
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