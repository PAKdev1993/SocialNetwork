<?php
use inc\Autoloader;

use core\Session\Session;
use app\Table\userModel\UserModel;
use app\App;

use core\Files\Images\Images;

//#todo $_FILES['logo'], $_POST['teamid'] et$_POST['logo'] peux etre passé en paramètres mais pas de controles dessus
if(isset($_POST['state']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

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
    if($_POST['state'] != 0 && $_POST['state'] != 1)
    {
        $valid = false;
    }
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * UPDATE NOTIFY MY NETWORK
     */
    if($valid)
    {
        $model = new UserModel(App::getDatabase());
        $model->updateNotifyMyNetwork($_POST['state']);
        Session::getInstance()->read('auth')->notifyMyNetwork = $_POST['state'];
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