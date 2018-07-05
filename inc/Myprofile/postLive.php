<?php
use inc\Autoloader;
use core\Timeline\Posts;
use app\Table\Posts\PostModel;
use core\Session\Session;
use app\Table\Live\LiveModel;

if(isset($_POST['text']))
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
    //--------------------------------------------------------------------------------------------------------------------
    /*
     * UPDATE NOTIFY MY NETWORK
     */
    if($valid)
    {
        $post =         new Posts();
        $liveModel =    new LiveModel();

        $liveContent = $liveModel->getMyLive()->embedhtml;

        $post->post($_POST['text'], false, false, $liveContent);
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