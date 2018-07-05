<?php
use inc\Autoloader;
use core\Session\Session;

use app\Table\UserModel\UserModel;
use app\Table\MyCommunity\MyCommunityModel;
use app\Table\Profile\ProfileCommunity\ProfileFollowerPart\displayProfileFollowerPart;

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
        if($model->amIFollowingFromIduser($iduser))
        {
            $model->removeFollowFromIdUser($iduser);
            $display = new displayProfileFollowerPart(false, $iduser);
            echo($display->showBtImNotFollowing());
            exit();
        }
        else{
            $model->saveFollowFromIdUser($iduser);
            $display = new displayProfileFollowerPart(false, $iduser);
            echo($display->showBtImFollowing());
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