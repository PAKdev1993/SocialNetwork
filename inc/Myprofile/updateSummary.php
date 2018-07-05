<?php
use inc\Autoloader;
use app\App;
use core\Session\Session;
use app\Table\UserModel\UserModel;
use app\Table\UserModel\displayUser;
use core\Profile\ProfileGamer;

if(isset($_POST['summary']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    //test si l'etat courant est bien celui de propriétaire du profil
    if(Session::getInstance()->read('current-state')['state'] == 'owner')
    {
        //update du summary
        $model = new UserModel(App::getDatabase());
        $model->saveMyGamerSummary($_POST['summary']);

        //on met a jour l'auth
        Session::getInstance()->read('auth')->gamersummary = $_POST['summary'];
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * NOTIFY MY NETWORK IF NESCESSARY
         */
        $GamerProfile = new ProfileGamer();
        $typeaction = $GamerProfile->getProfilePart();
        $action = "action_updated_gamerprofile";
        $elemType = NULL;
        $elemid = NULL;
        $GamerProfile->notifyMyNetwork($typeaction, $action, $elemType, $elemid);
        //--------------------------------------------------------------------------------------------------------------------
        /*
         * DISPLAY
         */
        //affichage du summary
        $summaryContent = $model->getMyGamerSummary();
        $display = new displayUser(Session::getInstance()->read('auth'), 'gamer.summary');
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