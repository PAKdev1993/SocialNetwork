<?php
use inc\Autoloader;

use core\Session\Session;
use app\Table\Live\displayLives;
use app\Table\Live\LiveModel;

require_once '../Autoloader.php';
Autoloader::register();

if(isset($_POST['action']))
{
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * SECURE CHECK
     */
    $valid = true;
    //test si l'etat courant est bien celui de propriétaire du profil
    if(Session::getInstance()->read('current-state')['state'] != 'owner')
    {
        $valid = false;
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * UPDATE NOTIFY MY NETWORK
     */
    if($valid)
    {
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * CHECK ACTION
         */
        $action = $_POST['action'];
        $model = new LiveModel();
        if($action == "on")
        {
            $model->turnMyLiveOn();
        }
        if($action == "off")
        {
            $model->turnMyLiveOff();
        }
        exit();
    }
}
else{
    echo('err');
    exit();
}