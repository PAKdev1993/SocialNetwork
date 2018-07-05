<?php
use inc\Autoloader;
use core\Session\Session;

use app\Table\UserModel\UserModel;
use app\Table\MyCommunity\MyCommunityModel;
use app\Table\Profile\ProfileCommunity\ProfileContactsPart\displayProfileContactPart;

//#todo page similaire a askForAdd de My community, factoriser ou que sais-je
if(isset($_POST['profileid']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $valid = true;
    $model = new UserModel();
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * SECURITY CONTROLS
     */
    if(Session::getInstance()->read('current-state')['state'] != 'viewer')
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

        if($model->amIContactFromIdUser($iduser))
        {
            $model->deleteContactFromIduser($iduser);
            $display = new displayProfileContactPart(false, $iduser);
            echo($display->showBtImNotContact());
            exit();
        }

        if($model->amIPendingContactFromIdUser($iduser))
        {
            $model->deleteAskContact($iduser);
            $display = new displayProfileContactPart(false, $iduser);
            echo($display->showBtImNotContact());
            exit();
        }
        else{
            //l'user n'est pas mon contact
            $model->askContact($iduser);
            $display = new displayProfileContactPart(false, $iduser);
            echo($display->showBtImPendingContact());
            exit();
        }

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