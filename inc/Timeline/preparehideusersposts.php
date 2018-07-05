<?php
use inc\Autoloader;
use core\Session\Session;
use app\Displays\displayAsk;

if(isset($_POST['datas']))
{
    require_once '../Autoloader.php';
    Autoloader::register();

    $postids = Session::getInstance()->read('PostDisplayed');
    $datasArray = json_decode($_POST['datas']);
    $postid = $datasArray->wepid;
    $userid = $datasArray->weid;
    $currentUser = Session::getInstance()->read('auth');

    //verification de l'existence du postid dans le tableau PostDisplayed n'est pas faites ici car faites lor du dusplay du mode edition

    //test si l'action courante est toujours le mode editing pour parer aux modifications html
    if(Session::getInstance()->read('current-action')['actionname'] == 'viewingPost' && Session::getInstance()->read('current-action')['idelem'] == $postid)
    {
        if($userid != $currentUser)
        {
            //affiche le viewer de confirmation
            $display = new displayAsk('hide-users-posts');
            echo($display->show());
            exit();
        }
    }
    else{
        echo('err');
    }
}
else{
    echo('err');
}