<?php
use inc\Autoloader;
use core\Session\Session;
use app\App;

use app\Table\MyCommunity\MyCommunityModel;
use app\Table\UserModel\UserModel;
use app\Table\MyCommunity\Contacts\Contact\displayContact;

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
        $model =        new MyCommunityModel();
        $userModel =    new UserModel(App::getDatabase());
        
        $iduser = $_POST['profileid'];
        
        

        if($model->isBlockedFromId($iduser))
        {
            $model->unblockMyContactFromId($iduser);
        }
        else{
            $model->blockMyContactFromId($iduser);
        }

        $contact = $model->getMyContactFromIduser($iduser);
        $display = new displayContact($contact);
        echo($display->show());
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