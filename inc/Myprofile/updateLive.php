<?php
use inc\Autoloader;

use core\Session\Session;
use app\Table\Live\displayLives;
use app\Table\Live\LiveModel;

require_once '../Autoloader.php';
Autoloader::register();

if(isset($_POST['embededHtml']) && isset($_POST['channelLink']) && isset($_POST['typeAction']))
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
         * SAVE LIVE
         */
        $model = new LiveModel();
        $model->saveMyLive($_POST['embededHtml'], $_POST['channelLink']);

        //--------------------------------------------------------------------------------------------------------------------
        /*
         * DISPLAY LIVE NEWLY UPDATED
         */
        $live = $model->getMyLive();
        $display = new displayLives($live, 'ProfileLive');
        echo($display->show());
        exit();
    }
}
else{
    echo('err');
    exit();
}