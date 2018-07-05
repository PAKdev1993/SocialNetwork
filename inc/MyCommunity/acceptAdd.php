<?php
use inc\Autoloader;
use core\Session\Session;

use app\Table\MyCommunity\MyCommunityModel;
use app\Table\MyCommunity\PendingContacts\PendingContact\displayPendingContact;

if(isset($_POST['profileid']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $valid = true;
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * SECURITY CONTROLS
     */
    if(Session::getInstance()->read('current-state')['state'] != 'owner')
    {
        $valid = false;
    }
    if($_POST['profileid'] == Session::getInstance()->read('auth')->pk_iduser)
    {
        $valid = false;
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * BLOCK
     */
    if($valid)
    {
        $iduser = $_POST['profileid'];
        $model =    new MyCommunityModel();
        $model->saveContact($iduser);
        echo('contact added to your list');
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